<div wire:poll.30s="refreshNotifications">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-bell mr-2"></i> Notificações</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Notificações</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">                
                <div class="col-12 my-2 text-right">
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <button
                            wire:click="markAllAsRead"
                            class="btn btn-sm btn-outline-success"
                            title="Marcar todas como lidas"
                        >
                            <i class="fas fa-check-double"></i>                            
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body p-0">

            @forelse($notifications as $notification)

                @php
                    $type = $notification->data['type'] ?? 'default';

                    $icon = match($type) {
                        'invoice_paid'       => 'fas fa-money-bill-wave',
                        'company_created'    => 'fas fa-building',
                        'reservation_created'=> 'fas fa-calendar-check',
                        'support_ticket'     => 'fas fa-life-ring',
                        'subscription'       => 'fas fa-credit-card',
                        'ArticleCreated' => 'fas fa-file-alt',
                        default              => 'fas fa-bell',
                    };

                    $color = match($notification->data['color'] ?? '') {
                        'success' => 'success',
                        'danger'  => 'danger',
                        'warning' => 'warning',
                        'info'    => 'info',
                        default   => 'secondary',
                    };
                @endphp

                <div class="
                    d-flex align-items-start p-3 border-bottom
                    {{ is_null($notification->read_at) ? 'bg-light' : 'bg-white' }}
                ">

                    {{-- Ícone --}}
                    <div class="mr-3 mt-1">
                        <span class="badge badge-{{ $color }} p-3">
                            <i class="{{ $icon }}"></i>
                        </span>
                    </div>

                    {{-- Conteúdo --}}
                    <div class="flex-grow-1">

                        <h6 class="mb-1 font-weight-bold text-dark">
                            {{ $notification->data['title'] ?? 'Nova notificação' }}
                        </h6>

                        <p class="mb-1 text-muted">
                            {{ $notification->data['message'] ?? '' }}
                        </p>

                        @if(!empty($notification->data['description']))
                            <small class="text-muted d-block">
                                {{ $notification->data['description'] }}
                            </small>
                        @endif

                        <small class="text-muted">
                            <i class="far fa-clock mr-1"></i>
                            {{ $notification->created_at->diffForHumans() }}
                        </small>

                    </div>

                    {{-- Ações --}}
                    <div class="ml-3 text-right">

                        @if(!empty($notification->data['url']))
                            <a
                                href="{{ $notification->data['url'] }}"
                                class="btn btn-sm btn-outline-primary mb-1"
                            >
                                <i class="fas fa-eye"></i>
                            </a>
                        @endif

                        @if(is_null($notification->read_at))
                            <button
                                wire:click="markAsRead('{{ $notification->id }}')"
                                class="btn btn-sm btn-outline-success"
                            >
                                <i class="fas fa-check"></i>
                            </button>
                        @endif

                    </div>

                </div>

            @empty

                <div class="text-center py-5">

                    <div class="mb-3">
                        <i class="far fa-bell-slash fa-4x text-muted"></i>
                    </div>

                    <h5 class="text-muted">
                        Nenhuma notificação encontrada
                    </h5>

                    <p class="text-muted mb-0">
                        Quando houver novas notificações elas aparecerão aqui.
                    </p>

                </div>

            @endforelse

        </div>

        {{-- Paginação --}}
        @if($notifications->hasPages())
            <div class="card-footer">
                {{ $notifications->links() }}
            </div>
        @endif

    </div>    
</div>
