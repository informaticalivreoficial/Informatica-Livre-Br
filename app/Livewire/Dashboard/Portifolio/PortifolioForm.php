<?php

namespace App\Livewire\Dashboard\Portifolio;

use App\Models\CatPortifolio;
use App\Models\Company;
use App\Models\Config;
use App\Models\Portifolio;
use App\Models\PortifolioGB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PortifolioForm extends Component
{
    use WithFileUploads;

    public ?Portifolio $portifolio = null;

    public $companies = [];

    public array $images = [];
    public $savedImages = [];

    public string $currentTab = 'dados'; 

    public ?string $data_inicio = null;
    public ?string $data_termino = null;

    public $categories;

    public array $tags = [];

    public ?int $display_marked_water = 0;

    public $exibir, $content, $name, $link, $headline, $slug, 
    $value, $category, $status, $thumb_legenda, $cat_pai, $company, $views;
    
    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:255',
            'category'     => 'required|exists:cat_portifolios,id',
            'company'      => 'required|exists:companies,id',
            'content'      => 'nullable|string',
            'headline'     => 'nullable|string|max:255',
            'link'         => 'nullable|url|max:255',
            'slug'         => 'nullable|string|max:255',
            'value'        => 'nullable|numeric|min:0',
            'data_inicio'  => 'nullable|date',
            'data_termino' => 'nullable|date|after_or_equal:data_inicio',
            'status'       => 'nullable|boolean',
            'exibir'       => 'nullable|boolean',
            'images.*'     => 'image|mimes:jpeg,jpg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'              => 'O nome do projeto é obrigatório.',
            'name.max'                   => 'O nome não pode ter mais de 255 caracteres.',
            'category.required'          => 'Selecione uma categoria.',
            'category.exists'            => 'A categoria selecionada é inválida.',
            'company.required'           => 'Selecione uma empresa.',
            'company.exists'             => 'A empresa selecionada é inválida.',
            'link.url'                   => 'A URL do site deve ser válida. Ex: https://www.site.com.br',
            'value.numeric'              => 'O valor deve ser um número.',
            'value.min'                  => 'O valor não pode ser negativo.',
            'data_inicio.date'           => 'A data de início é inválida.',
            'data_termino.date'          => 'A data de término é inválida.',
            'data_termino.after_or_equal'=> 'A data de término deve ser igual ou após a data de início.',
            'images.*.image'             => 'O arquivo enviado não é uma imagem válida.',
            'images.*.mimes'             => 'Formato não suportado. Use JPG, PNG ou WEBP.',
            'images.*.max'               => 'Cada imagem deve ter no máximo 2MB.',
        ];
    }

    private function fillFromPortifolio(Portifolio $portifolio): void
    {
        $this->name          = $portifolio->name;
        $this->category      = $portifolio->category;
        $this->company       = $portifolio->company;
        $this->content       = $portifolio->content;
        $this->headline      = $portifolio->headline;
        $this->link          = $portifolio->link;
        $this->slug          = $portifolio->slug;
        $this->value         = $portifolio->value;
        $this->data_inicio   = $portifolio->data_inicio?->toDateString();
        $this->data_termino  = $portifolio->data_termino?->toDateString();
        $this->status        = $portifolio->status;
        $this->exibir        = $portifolio->exibir;
        $this->thumb_legenda = $portifolio->thumb_legenda;
        $this->tags          = $portifolio->tags ? explode(',', $portifolio->tags) : [];
        $this->savedImages   = $portifolio->images;
    }

    public function mount(Portifolio $portifolio)
    {
        $this->portifolio = $portifolio;

        $this->companies = Company::orderBy('alias_name')->get();
        $this->categories = CatPortifolio::with('children')
        ->whereNull('id_pai')
        ->active()
        ->orderBy('title')
        ->get();

        if ($portifolio->exists) {            
            $this->fillFromPortifolio($portifolio);
        }
    }

    public function save(string $mode = 'draft'): void
    {
        $this->validate($this->rules());

        $data = [
            'name'          => $this->name,
            'category'      => $this->category,
            'company'       => $this->company,
            'content'       => $this->content,
            'headline'      => $this->headline,
            'link'          => $this->link,
            'slug'          => $this->slug ?? \Illuminate\Support\Str::slug($this->name),
            'value'         => $this->value,
            'data_inicio'   => $this->data_inicio,
            'data_termino'  => $this->data_termino,
            'status'        => $mode === 'published' ? 1 : 0,
            'exibir'        => $this->exibir,
            'thumb_legenda' => $this->thumb_legenda,
            'tags'          => implode(',', $this->tags ?? []),
        ];

        if ($this->portifolio->exists) {
            $this->portifolio->update($data);
        } else {
            $this->portifolio = Portifolio::create($data);
        }

        // Upload imagens
        if (!empty($this->images)) {
            $manager = new ImageManager(new Driver());

            foreach ($this->images as $index => $image) {
                $filename = uniqid() . '.webp';
                $path     = 'portifolio/' . $this->portifolio->id . '/' . $filename;

                $img     = $manager->read($image->getRealPath());
                $img->scaleDown(width: 1920);
                $encoded = $img->toWebp(85);

                Storage::disk('public')->put($path, $encoded);

                $hasCover = PortifolioGB::where('portifolio', $this->portifolio->id)
                    ->where('cover', true)
                    ->exists();

                PortifolioGB::create([
                    'portifolio' => $this->portifolio->id,
                    'path'       => $path,
                    'cover'      => (!$hasCover && $index === 0),
                ]);
            }

            $this->reset('images');
        }

        $this->portifolio->refresh();

        $this->dispatch('swal:success', [
            'title' => 'Sucesso!',
            'text'  => $this->portifolio->wasRecentlyCreated
                ? 'Trabalho cadastrado com sucesso!'
                : 'Trabalho atualizado com sucesso!',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);

        if ($this->portifolio->wasRecentlyCreated) {
            $this->redirect(route('portifolio.edit', $this->portifolio));
        }    
    }

    //Remover imagem temporária
    public function removeTempImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    //Remover imagem do Bd
    public function removeSavedImage($id)
    {
        $image = PortifolioGB::find($id);
        if ($image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
            $this->savedImages = collect($this->savedImages)->filter(fn ($img) => $img->id !== $id);
            $this->property->refresh(); // Para garantir que os dados estejam atualizados
        }
    }

    public function toggleCover($imageId)
    {
        $image = PortifolioGB::where('id', $imageId)->where('portifolio', $this->portifolio->id)->first();

        if ($image) {
            if ($image->cover) {
                // Se já é capa, remove
                $image->update(['cover' => 0]);
            } else {
                // Remove capa das outras e define esta
                PortifolioGB::where('portifolio', $this->portifolio->id)->update(['cover' => 0]);
                $image->update(['cover' => 1]);
            }

            // Atualiza a relação para refletir na view
            $this->portifolio->refresh();
        }
    }

    #[On('updateContent')]
    public function updateContent($value)
    {
        $this->content = $value;
    }

    public function updateImageOrder($order)
    {
        try {
            foreach ($order as $item) {
                PortifolioGB::where('id', $item['id'])
                    ->where('portifolio', $this->property->id)
                    ->update(['order_img' => $item['position']]);
            }
            
            // Atualiza a propriedade para refletir a nova ordem
            $this->property->refresh();
            
        } catch (\Exception $e) {
            $this->toastError('Erro ao atualizar ordem das imagens: ' . $e->getMessage());
        }
    }

    public function applyWatermarkImage($imageId)
    {
        $image = PortifolioGB::find($imageId);

        if ($image->watermarked) {
            return;
        }

        $config = Config::first();

        $manager = new ImageManager(new Driver());

        $img = $manager->read(storage_path('app/public/'.$image->path));
        $watermark = $manager->read(storage_path('app/public/'.$config->watermark));

        $img->place($watermark, 'bottom-right', 30, 30);
        $img->save();

        $image->update([
            'watermark' => true
        ]);

        $this->dispatch('swal:success', [
            'title' => false,
            'text' => 'Marca d’água aplicada!',
            'timer' => 2000,
            'showConfirmButton' => false
        ]);
    }

    public function updatedImages(): void
    {
        $hasHeic = collect($this->images)->contains(function ($image) {
            return strtolower($image->getClientOriginalExtension()) === 'heic';
        });

        if ($hasHeic) {
            $this->dispatch('swal:warning', [
                'title' => 'Formato não suportado!',
                'text'  => 'Imagens no formato HEIC (iPhone) não são aceitas. Converta para JPG ou PNG antes de enviar.',
                'icon'  => 'warning',
            ]);

            $this->reset('images');
        }
    }

    public function render()
    {
        $title = $this->portifolio->exists ? 'Editar Trabalho' : 'Cadastrar Trabalho';
        return view('livewire.dashboard.portifolio.portifolio-form');
    }
}
