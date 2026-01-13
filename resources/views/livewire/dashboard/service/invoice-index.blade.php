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
                <div class="col-12 col-sm-6 my-2">
                    <p class="text-sm text-gray-500">
                        {{ $subscription->company->alias_name }} —
                        {{ $subscription->service->name }}
                    </p>
                </div>
                <div class="col-12 col-sm-6 my-2 text-right">
                    <button
                        wire:click="openCreateModal"
                        class="btn btn-sm btn-default"
                    >
                        <i class="fas fa-plus mr-2"></i> Criar Fatura
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Status</th>
                        <th class="text-right">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($subscription->invoices as $invoice)
                        <tr>
                            <td>#{{ $invoice->id }}</td>

                            <td>
                                {{ $invoice->due_date->format('d/m/Y') }}
                            </td>

                            <td>
                                R$ {{ number_format($invoice->amount, 2, ',', '.') }}
                            </td>

                            <td>
                                <span class="px-2 py-1 rounded text-xs
                                    @class([
                                        'bg-green-100 text-green-700' => $invoice->status === 'paid',
                                        'bg-yellow-100 text-yellow-700' => $invoice->status === 'pending',
                                        'bg-red-100 text-red-700' => $invoice->status === 'canceled',
                                    ])
                                ">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>

                            <td class="text-right space-x-2">
                                <button class="btn btn-xs btn-outline">
                                    Ver
                                </button>

                                @if ($invoice->status === 'pending')
                                    <button class="btn btn-xs btn-success">
                                        Marcar paga
                                    </button>
                                @endif
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

        <div class="card-footer clearfix">
            Paginação
        </div>
    </div>

    @if($showCreateModal)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-[10000]">
            <div class="bg-white rounded-lg w-full max-w-md p-6 space-y-4">

                <h3 class="text-lg font-semibold">Nova fatura</h3>

                <div>
                    <label class="label">Valor</label>
                    <input type="number" step="0.01" wire:model="amount" class="input">
                    @error('amount') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="label">Vencimento</label>
                    <input type="date" wire:model="due_date" class="input">
                    @error('due_date') <span class="text-red-500">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end gap-2">
                    <button
                        wire:click="$set('showCreateModal', false)"
                        class="btn btn-sm btn-secondary"
                    >
                        Cancelar
                    </button>

                    <button
                        wire:click="createInvoice"
                        class="btn btn-sm btn-primary"
                    >
                        Criar fatura
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
