<?php

namespace App\Livewire\Dashboard\Loja;

use App\Models\Produto;
use App\Models\ProdutoPlano;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Produtos extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    public string $status = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function toggleStatus(int $id): void
    {
        $produto = Produto::findOrFail($id);

        $produto->update(['status' => ! $produto->status]);
    }

    public function toggleDestaque(int $id): void
    {
        $produto = Produto::findOrFail($id);

        $produto->update(['destaque' => ! $produto->destaque]);
    }

    public function confirmDelete(int $id): void
    {
        $produto = Produto::findOrFail($id);

        $this->dispatch('swal:confirm', [
            'title' => 'Excluir produto?',
            'text' => "O produto \"{$produto->nome}\" e seus planos serão removidos.",
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteProduto',
            'confirmParams' => [$id],
        ]);
    }

    #[On('deleteProduto')]
    public function deleteProduto(int $id): void
    {
        $produto = Produto::findOrFail($id);

        if ($produto->pedidoItens()->exists()) {
            $this->dispatch('swal', [
                'title' => 'Não é possível excluir',
                'text' => 'Este produto possui pedidos vinculados. Desative-o em vez de excluir.',
                'icon' => 'error',
            ]);

            return;
        }

        foreach ($produto->images as $image) {
            Storage::disk()->delete($image->path);
        }

        $produto->planos()->delete();
        $produto->images()->delete();
        $produto->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text' => 'Produto removido com sucesso.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function render()
    {
        $produtos = Produto::query()
            ->with(['images', 'planos'])
            ->withCount('pedidoItens')
            ->when($this->search, fn ($query) => $query->where(function ($query) {
                $query->where('nome', 'like', "%{$this->search}%")
                    ->orWhere('slug', 'like', "%{$this->search}%")
                    ->orWhere('headline', 'like', "%{$this->search}%");
            }))
            ->when($this->status !== '', fn ($query) => $query->where('status', $this->status === '1'))
            ->ordenado()
            ->paginate(15);

        $stats = [
            'total' => Produto::count(),
            'ativos' => Produto::where('status', true)->count(),
            'destaques' => Produto::where('destaque', true)->count(),
            'planos' => ProdutoPlano::count(),
        ];

        return view('livewire.dashboard.loja.produtos', [
            'produtos' => $produtos,
            'stats' => $stats,
        ])->with('title', 'Loja — Produtos');
    }
}
