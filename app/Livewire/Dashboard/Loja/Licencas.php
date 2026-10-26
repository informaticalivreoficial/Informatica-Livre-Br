<?php

namespace App\Livewire\Dashboard\Loja;

use App\Models\Licenca;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Licencas extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    public string $status = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $licencaStatus = 'aguardando';
    public string $url_sistema = '';
    public string $url_painel = '';
    public string $credenciais = '';
    public string $notas = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function rules(): array
    {
        return [
            'licencaStatus' => 'required|in:aguardando,ativa,suspensa,cancelada',
            'url_sistema' => 'nullable|url|max:255',
            'url_painel' => 'nullable|url|max:255',
            'credenciais' => 'nullable|string',
            'notas' => 'nullable|string',
        ];
    }

    public function openEdit(int $id): void
    {
        $licenca = Licenca::with(['produto', 'pedido'])->findOrFail($id);

        $this->editingId = $licenca->id;
        $this->licencaStatus = $licenca->status;
        $this->url_sistema = $licenca->url_sistema ?? '';
        $this->url_painel = $licenca->url_painel ?? '';
        $this->credenciais = $licenca->credenciais ?? '';
        $this->notas = $licenca->notas ?? '';
        $this->showModal = true;
    }

    public function close(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function save(): void
    {
        $this->validate();

        $licenca = Licenca::findOrFail($this->editingId);

        $licenca->update([
            'status' => $this->licencaStatus,
            'url_sistema' => $this->url_sistema ?: null,
            'url_painel' => $this->url_painel ?: null,
            'credenciais' => $this->credenciais ?: null,
            'notas' => $this->notas ?: null,
        ]);

        $this->close();

        $this->dispatch('swal', [
            'title' => 'Sucesso',
            'text' => 'Licença atualizada com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function confirmDelete(int $id): void
    {
        $licenca = Licenca::findOrFail($id);

        $this->dispatch('swal:confirm', [
            'title' => 'Excluir licença?',
            'text' => "A licença {$licenca->chave} será removida.",
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteLicenca',
            'confirmParams' => [$id],
        ]);
    }

    #[On('deleteLicenca')]
    public function deleteLicenca(int $id): void
    {
        $licenca = Licenca::findOrFail($id);

        $licenca->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text' => 'Licença removida com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->licencaStatus = 'aguardando';
        $this->url_sistema = '';
        $this->url_painel = '';
        $this->credenciais = '';
        $this->notas = '';
        $this->resetValidation();
    }

    public function render()
    {
        $licencas = Licenca::query()
            ->with(['produto', 'pedido'])
            ->when($this->search, fn ($query) => $query->where(function ($query) {
                $query->where('chave', 'like', "%{$this->search}%")
                    ->orWhere('nome', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->status, fn ($query) => $query->where('status', $this->status))
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'total' => Licenca::count(),
            'ativas' => Licenca::where('status', 'ativa')->count(),
            'aguardando' => Licenca::where('status', 'aguardando')->count(),
            'suspensas' => Licenca::whereIn('status', ['suspensa', 'cancelada'])->count(),
        ];

        return view('livewire.dashboard.loja.licencas', [
            'licencas' => $licencas,
            'stats' => $stats,
        ])->with('title', 'Loja — Licenças');
    }
}
