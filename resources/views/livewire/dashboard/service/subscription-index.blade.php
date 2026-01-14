<div>
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-store mr-2"></i> Pedidos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Pedidos</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12 col-sm-6 my-2">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input
                            type="text"
                            wire:model.debounce.500ms="searchCompany"
                            placeholder="Empresa"
                            class="input"
                        >

                        <input
                            type="text"
                            wire:model.debounce.500ms="searchService"
                            placeholder="Serviço"
                            class="input"
                        >

                        <select wire:model="status" class="input">
                            <option value="">Todos status</option>
                            <option value="active">Ativo</option>
                            <option value="paused">Pausado</option>
                            <option value="canceled">Cancelado</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-sm-6 my-2 text-right">
                    <a
                        href="{{ route('services.subscriptions.create') }}"
                        class="btn btn-sm btn-default"
                    >
                        <i class="fas fa-plus mr-2"></i> Cadastrar
                </a>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Serviço</th>
                        <th>Valor</th>
                        <th>Intervalo</th>
                        <th>Próx. cobrança</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($subscriptions as $subscription)
                        <tr>
                            <td>
                                {{ $subscription->company->alias_name }}
                            </td>

                            <td>
                                {{ $subscription->service->name }}
                            </td>

                            <td>
                                R$ {{ number_format($subscription->amount, 2, ',', '.') }}
                            </td>

                            <td>
                                {{ $subscription->interval?->label() ?? 'Único' }}
                            </td>

                            <td>
                                 {{ $subscription->next_billing_at?->format('d/m/Y') ?? '-' }}
                            </td>                            

                            <td>
                                <span class="
                                    px-2 py-1 rounded text-xs
                                    @if($subscription->status === \App\Enums\SubscriptionStatus::ACTIVE)
                                        bg-green-100 text-green-700
                                    @elseif($subscription->status === \App\Enums\SubscriptionStatus::PAUSED)
                                        bg-yellow-100 text-yellow-700
                                    @elseif($subscription->status === \App\Enums\SubscriptionStatus::CANCELED)
                                        bg-red-100 text-red-700
                                    @endif
                                ">
                                    {{ $subscription->status->label() }}
                                </span>
                            </td>

                            <td class="text-right"> 
                                
                                <a href="{{ route('services.invoices.index', $subscription->id) }}" class="btn btn-xs btn-success text-white" title="Ver Faturas"><i class="fas fa-credit-card"></i></a>
                                
                                <a href="{{ route('services.subscriptions.show', $subscription) }}" class="btn btn-xs btn-info text-white" title="Visualizar"><i class="fas fa-search"></i></a>
                                
                                <a href="{{ route('services.subscriptions.edit', $subscription) }}" class="btn btn-xs btn-default" title="Editar"><i class="fas fa-pen"></i></a>                        
                                <button 
                                        wire:click="confirmDelete({{ $subscription->id }})"
                                        type="button"  title="Excluir"
                                        class="btn btn-xs bg-danger text-white">
                                        <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Nenhum pedido cadastrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer clearfix">
            {{ $subscriptions->links() }}
        </div>
    </div>
</div>
