<div>
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-store mr-2"></i> Faturas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('services.subscriptions.index') }}">Pedidos</a></li>
                        <li class="breadcrumb-item active">Faturas</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">

                <div class="col-md-6">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Buscar empresa ou ID da fatura..."
                        wire:model.live.debounce.500ms="search"
                    >
                </div>

                <div class="col-md-3">
                    <select
                        class="form-control"
                        wire:model.live="status"
                    >
                        <option value="">Todos os status</option>
                        <option value="pending">Pendente</option>
                        <option value="paid">Pago</option>
                        <option value="canceled">Cancelado</option>
                        <option value="refunded">Estornado</option>
                    </select>
                </div>

            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Empresa</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr>
                            <td>#{{ $invoice->id }}</td>

                            <td>
                                {{ $invoice->company->alias_name }}
                            </td>

                            <td>
                                {{ $invoice->due_date->format('d/m/Y') }}
                            </td>

                            <td>
                                R$ {{ number_format($invoice->amount, 2, ',', '.') }}
                            </td>

                            <td>
                                <span class="badge
                                    @if($invoice->status === 'paid') badge-success
                                    @elseif($invoice->status === 'pending') badge-warning
                                    @elseif($invoice->status === 'canceled') badge-danger
                                    @endif
                                ">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>

                            <td class="text-right space-x-2">
                                <button
                                    wire:click="syncInvoice({{ $invoice->id }})"
                                    wire:loading.attr="disabled"
                                    class="btn btn-sm btn-outline-primary"
                                    title="Atualizar Status"
                                >
                                    <i class="fas fa-sync"></i>
                                </button>

                                @if ($invoice->status !== 'paid')
                                    <button
                                        wire:click="generateBoleto({{ $invoice->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="generateBoleto({{ $invoice->id }})"
                                        class="btn btn-xs btn-outline"
                                        title="Gerar Boleto"
                                    >
                                        <span wire:loading.remove wire:target="generateBoleto({{ $invoice->id }})">
                                            <i class="fas fa-barcode mr-1"></i>
                                            {{ $invoice->payment_url ? 'Ver Boleto' : 'Gerar Boleto' }}
                                        </span>
                                        <span wire:loading wire:target="generateBoleto({{ $invoice->id }})">
                                            <i class="fas fa-spinner fa-spin mr-1"></i> Aguarde...
                                        </span>
                                    </button>
                                @endif                                

                                @if (
                                    $invoice->status === 'pending' || 
                                    $invoice->status === 'canceled' || 
                                    $invoice->status === 'failed')
                                    <button
                                        wire:click="markAsPaid({{ $invoice->id }})"
                                        wire:confirm="Confirmar pagamento da fatura #{{ $invoice->id }}?"
                                        class="btn btn-xs btn-success"
                                        title="Marcar como Pago"
                                    >
                                        <i class="fas fa-check mr-1"></i> Marcar paga
                                    </button>
                                @endif

                                <button 
                                    wire:click="confirmDelete({{ $invoice->id }})"
                                    type="button" title="Excluir"
                                    class="btn btn-xs bg-danger text-white"
                                    title="Excluir Fatura"
                                    >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500">
                                Nenhuma fatura encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
            <div class="card-footer clearfix">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

    

</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('openUrl', ({ url }) => {
                window.open(url, '_blank');
            });
        });
    </script>
@endpush
