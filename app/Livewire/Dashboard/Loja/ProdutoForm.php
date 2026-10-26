<?php

namespace App\Livewire\Dashboard\Loja;

use App\Models\Produto;
use App\Models\ProdutoGB;
use App\Models\ProdutoPlano;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProdutoForm extends Component
{
    use WithFileUploads;

    public ?Produto $produto = null;

    public string $nome = '';
    public string $slug = '';
    public string $headline = '';
    public string $descricao = '';
    public string $conteudo = '';
    public string $demo_url = '';

    public array $features = [];
    public string $featureInput = '';

    public bool $destaque = false;
    public bool $status = true;
    public int $ordem = 0;

    public array $images = [];
    public $savedImages = [];

    public bool $showPlanoModal = false;
    public ?int $editingPlanoId = null;
    public string $planoNome = '';
    public string $planoDescricao = '';
    public float $planoPreco = 0;
    public ?float $planoPrecoDe = null;
    public string $planoBillingType = 'one_time';
    public string $planoBillingInterval = 'monthly';
    public array $planoIncluso = [];
    public string $planoInclusoInput = '';
    public bool $planoDestaque = false;
    public bool $planoStatus = true;
    public int $planoOrdem = 0;

    public function mount()
    {
        if (! $this->produto || ! $this->produto->exists) {
            return;
        }

        $this->fill([
            'nome' => $this->produto->nome,
            'slug' => $this->produto->slug,
            'headline' => $this->produto->headline ?? '',
            'descricao' => $this->produto->descricao ?? '',
            'conteudo' => $this->produto->conteudo ?? '',
            'demo_url' => $this->produto->demo_url ?? '',
            'features' => $this->produto->features ?? [],
            'destaque' => (bool) $this->produto->destaque,
            'status' => (bool) $this->produto->status,
            'ordem' => (int) $this->produto->ordem,
        ]);

        $this->savedImages = $this->produto->images()->orderBy('order_img')->get();
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('produtos', 'slug')->ignore($this->produto?->id),
            ],
            'headline' => 'nullable|string|max:255',
            'descricao' => 'nullable|string',
            'conteudo' => 'nullable|string',
            'demo_url' => 'nullable|url|max:255',
            'features' => 'array',
            'features.*' => 'nullable|string|max:255',
            'destaque' => 'boolean',
            'status' => 'boolean',
            'ordem' => 'nullable|integer|min:0',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:2048',
        ];
    }

    public function planoRules(): array
    {
        return [
            'planoNome' => 'required|string|max:255',
            'planoDescricao' => 'nullable|string',
            'planoPreco' => 'required|numeric|min:0.01',
            'planoPrecoDe' => 'nullable|numeric|min:0',
            'planoBillingType' => 'required|in:one_time,recurring',
            'planoBillingInterval' => 'required_if:planoBillingType,recurring|nullable|in:monthly,quarterly,semiannual,yearly',
            'planoIncluso' => 'array',
            'planoIncluso.*' => 'nullable|string|max:255',
            'planoDestaque' => 'boolean',
            'planoStatus' => 'boolean',
            'planoOrdem' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'Informe o nome do produto.',
            'slug.unique' => 'Já existe um produto com este slug.',
            'demo_url.url' => 'Informe uma URL válida para a demonstração.',
            'images.*.image' => 'O arquivo enviado não é uma imagem válida.',
            'images.*.mimes' => 'Formato não suportado. Use JPG, PNG ou WEBP.',
            'images.*.max' => 'Cada imagem deve ter no máximo 2MB.',
            'planoNome.required' => 'Informe o nome do plano.',
            'planoPreco.required' => 'Informe o preço do plano.',
            'planoPreco.numeric' => 'Informe um valor válido para o preço.',
            'planoBillingInterval.required_if' => 'Informe o intervalo de cobrança do plano recorrente.',
        ];
    }

    public function updatedImages(): void
    {
        $hasHeic = collect($this->images)->contains(
            fn ($image) => strtolower($image->getClientOriginalExtension()) === 'heic'
        );

        if ($hasHeic) {
            $this->dispatch('swal', [
                'title' => 'Formato não suportado',
                'text' => 'Imagens HEIC do iPhone não são aceitas. Converta para JPG ou PNG.',
                'icon' => 'warning',
            ]);

            $this->reset('images');
        }
    }

    public function addFeature(): void
    {
        $feature = trim($this->featureInput);

        if ($feature === '') {
            return;
        }

        $this->features[] = $feature;
        $this->featureInput = '';
    }

    public function removeFeature(int $index): void
    {
        unset($this->features[$index]);
        $this->features = array_values($this->features);
    }

    public function removeTempImage(int $index): void
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    public function removeSavedImage(int $id): void
    {
        $image = ProdutoGB::where('id', $id)
            ->where('produto', $this->produto?->id)
            ->first();

        if (! $image) {
            return;
        }

        Storage::disk()->delete($image->path);
        $image->delete();

        $this->savedImages = $this->produto->images()->orderBy('order_img')->get();
    }

    public function toggleCover(int $id): void
    {
        $image = ProdutoGB::where('id', $id)
            ->where('produto', $this->produto?->id)
            ->first();

        if (! $image) {
            return;
        }

        $this->produto->images()->update(['cover' => false]);

        $image->update(['cover' => ! $image->cover]);

        $this->savedImages = $this->produto->images()->orderBy('order_img')->get();
    }

    public function save(): void
    {
        $this->validate();

        $slug = Str::slug($this->slug ?: $this->nome);

        if ($slug === '') {
            $slug = Str::random(8);
        }

        $data = [
            'nome' => $this->nome,
            'slug' => $slug,
            'headline' => $this->headline ?: null,
            'descricao' => $this->descricao ?: null,
            'conteudo' => $this->conteudo ?: null,
            'demo_url' => $this->demo_url ?: null,
            'features' => array_values(array_filter($this->features)),
            'destaque' => $this->destaque,
            'status' => $this->status,
            'ordem' => $this->ordem,
        ];

        $criado = false;

        if ($this->produto?->exists) {
            $this->produto->update($data);
        } else {
            $this->produto = Produto::create($data);
            $criado = true;
        }

        $this->uploadImages();
        $this->produto->refresh();
        $this->savedImages = $this->produto->images()->orderBy('order_img')->get();
        $this->slug = $this->produto->slug;

        $this->dispatch('swal', [
            'title' => 'Sucesso',
            'text' => $criado ? 'Produto cadastrado com sucesso.' : 'Produto atualizado com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);

        if ($criado) {
            $this->redirect(route('loja.produtos.edit', $this->produto));
        }
    }

    #[On('updateContent')]
    public function updateContent($value): void
    {
        $this->conteudo = $value;
    }

    private function uploadImages(): void    {
        if (empty($this->images)) {
            return;
        }

        $manager = new ImageManager(new Driver());

        foreach ($this->images as $index => $image) {
            $path = 'produtos/' . $this->produto->id . '/' . uniqid() . '.webp';

            $img = $manager->read($image->getRealPath());
            $img->scaleDown(width: 1600);

            Storage::disk()->put($path, (string) $img->toWebp(85));

            $temCapa = $this->produto->images()->where('cover', true)->exists();

            ProdutoGB::create([
                'produto' => $this->produto->id,
                'path' => $path,
                'cover' => ! $temCapa && $index === 0,
                'order_img' => $this->produto->images()->count(),
            ]);
        }

        $this->reset('images');
    }

    public function openPlanoCreate(): void
    {
        $this->resetPlanoForm();
        $this->planoOrdem = ($this->produto?->planos()->max('ordem') ?? 0) + 1;
        $this->showPlanoModal = true;
    }

    public function openPlanoEdit(int $id): void
    {
        $plano = ProdutoPlano::where('produto_id', $this->produto?->id)->findOrFail($id);

        $this->editingPlanoId = $plano->id;
        $this->planoNome = $plano->nome;
        $this->planoDescricao = $plano->descricao ?? '';
        $this->planoPreco = (float) $plano->preco;
        $this->planoPrecoDe = $plano->preco_de !== null ? (float) $plano->preco_de : null;
        $this->planoBillingType = $plano->billing_type?->value ?? 'one_time';
        $this->planoBillingInterval = $plano->billing_interval?->value ?? 'monthly';
        $this->planoIncluso = array_values((array) $plano->incluso);
        $this->planoDestaque = $plano->destaque;
        $this->planoStatus = $plano->status;
        $this->planoOrdem = $plano->ordem;
        $this->showPlanoModal = true;
    }

    public function closePlano(): void
    {
        $this->showPlanoModal = false;
        $this->resetPlanoForm();
    }

    public function addIncluso(): void
    {
        $item = trim($this->planoInclusoInput);

        if ($item === '') {
            return;
        }

        $this->planoIncluso[] = $item;
        $this->planoInclusoInput = '';
    }

    public function removeIncluso(int $index): void
    {
        unset($this->planoIncluso[$index]);
        $this->planoIncluso = array_values($this->planoIncluso);
    }

    public function savePlano(): void
    {
        if (! $this->produto?->exists) {
            $this->dispatch('swal', [
                'title' => 'Salve o produto primeiro',
                'text' => 'Cadastre o produto antes de criar os planos.',
                'icon' => 'info',
            ]);

            return;
        }

        $this->validate($this->planoRules());

        $preco = round((float) $this->planoPreco, 2);
        $precoDe = $this->planoPrecoDe ? round((float) $this->planoPrecoDe, 2) : null;

        if ($precoDe !== null && $precoDe <= $preco) {
            $this->addError('planoPrecoDe', 'O preço riscado deve ser maior que o preço atual.');
            return;
        }

        if ($this->planoDestaque) {
            $this->produto->planos()->update(['destaque' => false]);
        }

        ProdutoPlano::updateOrCreate(
            ['id' => $this->editingPlanoId],
            [
                'produto_id' => $this->produto->id,
                'nome' => $this->planoNome,
                'descricao' => $this->planoDescricao ?: null,
                'preco' => $preco,
                'preco_de' => $precoDe,
                'billing_type' => $this->planoBillingType,
                'billing_interval' => $this->planoBillingType === 'recurring' ? $this->planoBillingInterval : null,
                'incluso' => array_values(array_filter($this->planoIncluso)),
                'destaque' => $this->planoDestaque,
                'status' => $this->planoStatus,
                'ordem' => $this->planoOrdem,
            ]
        );

        $this->closePlano();

        $this->dispatch('swal', [
            'title' => 'Sucesso',
            'text' => 'Plano salvo com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function togglePlanoStatus(int $id): void
    {
        $plano = ProdutoPlano::where('produto_id', $this->produto?->id)->findOrFail($id);

        $plano->update(['status' => ! $plano->status]);
    }

    public function togglePlanoDestaque(int $id): void
    {
        $plano = ProdutoPlano::where('produto_id', $this->produto?->id)->findOrFail($id);

        if (! $plano->destaque) {
            $this->produto->planos()->update(['destaque' => false]);
        }

        $plano->update(['destaque' => ! $plano->destaque]);
    }

    public function confirmDeletePlano(int $id): void
    {
        $plano = ProdutoPlano::where('produto_id', $this->produto?->id)->findOrFail($id);

        $this->dispatch('swal:confirm', [
            'title' => 'Excluir plano?',
            'text' => "O plano \"{$plano->nome}\" será removido.",
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deletePlano',
            'confirmParams' => [$id],
        ]);
    }

    #[On('deletePlano')]
    public function deletePlano(int $id): void
    {
        $plano = ProdutoPlano::where('produto_id', $this->produto?->id)->findOrFail($id);

        if ($plano->pedidoItens()->exists()) {
            $this->dispatch('swal', [
                'title' => 'Não é possível excluir',
                'text' => 'Este plano já foi vendido. Desative-o em vez de excluir.',
                'icon' => 'error',
            ]);

            return;
        }

        $plano->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text' => 'Plano removido com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    private function resetPlanoForm(): void
    {
        $this->editingPlanoId = null;
        $this->planoNome = '';
        $this->planoDescricao = '';
        $this->planoPreco = 0;
        $this->planoPrecoDe = null;
        $this->planoBillingType = 'one_time';
        $this->planoBillingInterval = 'monthly';
        $this->planoIncluso = [];
        $this->planoInclusoInput = '';
        $this->planoDestaque = false;
        $this->planoStatus = true;
        $this->planoOrdem = 0;
        $this->resetValidation();
    }

    public function render()
    {
        $planos = $this->produto?->exists
            ? $this->produto->planos()->orderBy('ordem')->get()
            : collect();

        return view('livewire.dashboard.loja.produto-form', [
            'planos' => $planos,
        ])->with('title', $this->produto?->exists ? 'Editar produto' : 'Novo produto');
    }
}
