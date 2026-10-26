<?php

namespace App\Livewire\Dashboard\Loja;

use App\Models\Pedido;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Pedidos extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    public string $statusPagamento = '';

    public string $status = '';

    public bool $showModal = false;

    public ?Pedido $pedido = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusPagamento(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function open(int $id): void
    {
        $this->pedido = Pedido::with(['itens.produto', 'itens.plano', 'licencas', 'company', 'pai'])
            ->findOrFail($id);

        $this->showModal = true;
    }

    public function close(): void
    {
        $this->showModal = false;
        $this->pedido = null;
    }

    public function confirmPaid(int $id): void
    {
        $pedido = Pedido::findOrFail($id);

        $this->dispatch('swal:confirm', [
            'title' => 'Marcar pedido como pago?',
            'text' => "O pedido {$pedido->codigo} será confirmado e as licenças serão geradas.",
            'icon' => 'question',
            'confirmButtonText' => 'Sim, confirmar pagamento',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'pedidoPago',
            'confirmParams' => [$id],
        ]);
    }

    #[On('pedidoPago')]
    public function marcarComoPago(int $id): void
    {
        $pedido = Pedido::findOrFail($id);

        $pedido->markAsPaid();

        $this->refreshPedido();

        $this->dispatch('swal', [
            'title' => 'Pagamento confirmado',
            'text' => 'Pedido confirmado e licenças geradas.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function confirmCancel(int $id): void
    {
        $pedido = Pedido::findOrFail($id);

        $this->dispatch('swal:confirm', [
            'title' => 'Cancelar pedido?',
            'text' => "O pedido {$pedido->codigo} será cancelado.",
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, cancelar',
            'cancelButtonText' => 'Voltar',
            'confirmEvent' => 'pedidoCancelado',
            'confirmParams' => [$id],
        ]);
    }

    #[On('pedidoCancelado')]
    public function cancelar(int $id): void
    {
        $pedido = Pedido::findOrFail($id);

        try {
            $pedido->markAsCanceled();
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Não foi possível cancelar',
                'text' => $e->getMessage(),
                'icon' => 'error',
            ]);

            return;
        }

        $this->refreshPedido();

        $this->dispatch('swal', [
            'title' => 'Pedido cancelado',
            'text' => 'O pedido foi cancelado.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function confirmRefund(int $id): void
    {
        $pedido = Pedido::findOrFail($id);

        $this->dispatch('swal:confirm', [
            'title' => 'Reembolsar pedido?',
            'text' => "O pedido {$pedido->codigo} será marcado como reembolsado e as licenças canceladas.",
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, reembolsar',
            'cancelButtonText' => 'Voltar',
            'confirmEvent' => 'pedidoReembolsado',
            'confirmParams' => [$id],
        ]);
    }

    #[On('pedidoReembolsado')]
    public function reembolsar(int $id): void
    {
        $pedido = Pedido::findOrFail($id);

        try {
            $pedido->markAsRefunded();
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Não foi possível reembolsar',
                'text' => $e->getMessage(),
                'icon' => 'error',
            ]);

            return;
        }

        $this->refreshPedido();

        $this->dispatch('swal', [
            'title' => 'Pedido reembolsado',
            'text' => 'As licenças vinculadas foram canceladas.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function gerarLicencas(int $id): void
    {
        $pedido = Pedido::findOrFail($id);

        $pedido->gerarLicencas();

        $this->refreshPedido();

        $this->dispatch('swal', [
            'title' => 'Licenças geradas',
            'text' => 'As licenças pendentes do pedido foram criadas.',
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function gerarLinkPagamento(int $id): void
    {
        $pedido = Pedido::findOrFail($id);

        try {
            app(\App\Services\PagBankService::class)->createPedidoCheckout($pedido);
        } catch (\Throwable $e) {
            $this->dispatch('swal', [
                'title' => 'Não foi possível gerar o link',
                'text' => $e->getMessage(),
                'icon' => 'error',
            ]);

            return;
        }

        app(\App\Services\LojaNotificacaoService::class)->pedidoRecebido($pedido->fresh());

        $this->refreshPedido();

        $this->dispatch('swal', [
            'title' => 'Link de pagamento gerado',
            'text' => 'O link do PagBank foi gerado e enviado para o e-mail do cliente.',
            'icon' => 'success',
            'timer' => 2500,
            'showConfirmButton' => false,
        ]);
    }

    public function pausarRecorrencia(int $id): void
    {
        $this->alterarRecorrencia($id, fn (Pedido $pedido) => $pedido->pausarRecorrencia(), 'Cobrança recorrente pausada.');
    }

    public function retomarRecorrencia(int $id): void
    {
        $this->alterarRecorrencia($id, fn (Pedido $pedido) => $pedido->retomarRecorrencia(), 'Cobrança recorrente reativada.');
    }

    public function cancelarRecorrencia(int $id): void
    {
        $this->alterarRecorrencia($id, fn (Pedido $pedido) => $pedido->cancelarRecorrencia(), 'Cobrança recorrente cancelada.');
    }

    private function alterarRecorrencia(int $id, callable $acao, string $mensagem): void
    {
        $pedido = Pedido::findOrFail($id);

        try {
            $acao($pedido);
        } catch (\Throwable $e) {
            $this->dispatch('swal', [
                'title' => 'Não foi possível alterar a recorrência',
                'text' => $e->getMessage(),
                'icon' => 'error',
            ]);

            return;
        }

        $this->refreshPedido();

        $this->dispatch('swal', [
            'title' => 'Recorrência atualizada',
            'text' => $mensagem,
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    private function refreshPedido(): void
    {
        if ($this->pedido) {
            $this->pedido = $this->pedido->fresh(['itens.produto', 'itens.plano', 'licencas', 'company', 'pai']);
        }
    }

    public function render()
    {
        $pedidos = Pedido::query()
            ->with(['itens', 'licencas'])
            ->when($this->search, fn ($query) => $query->where(function ($query) {
                $query->where('codigo', 'like', "%{$this->search}%")
                    ->orWhere('nome', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->statusPagamento, fn ($query) => $query->where('status_pagamento', $this->statusPagamento))
            ->when($this->status, fn ($query) => $query->where('status', $this->status))
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'total' => Pedido::count(),
            'pendentes' => Pedido::where('status_pagamento', 'pendente')->count(),
            'pagos' => Pedido::where('status_pagamento', 'pago')->count(),
            'receita' => 'R$ ' . number_format((float) Pedido::where('status_pagamento', 'pago')->sum('total'), 2, ',', '.'),
            'recorrentes' => Pedido::recorrentesAtivos()->count(),
        ];

        return view('livewire.dashboard.loja.pedidos', [
            'pedidos' => $pedidos,
            'stats' => $stats,
        ])->with('title', 'Loja — Pedidos');
    }
}
