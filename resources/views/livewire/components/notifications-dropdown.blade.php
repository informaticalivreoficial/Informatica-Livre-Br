<div wire:poll.30s="loadNotifications">
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>

            @if($unreadNotificationsCount > 0)
                <span class="badge badge-danger navbar-badge">
                    {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                </span>
            @endif
        </a>

        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right shadow" style="width: 380px">
            
            {{-- Cabeçalho --}}
            <div class="dropdown-header d-flex justify-content-between align-items-center bg-light">
                <strong>
                    🔔 {{ $unreadNotificationsCount }} {{ Str::plural('Notificação', $unreadNotificationsCount) }}
                </strong>

                @if($unreadNotificationsCount > 0)
                    <button
                        wire:click="markAllAsRead"
                        class="btn btn-sm btn-outline-success"
                        title="Marcar todas como lidas"
                    >
                        <i class="fas fa-check-double"></i>
                    </button>
                @endif
            </div>

            <div class="dropdown-divider m-0"></div>

            {{-- Lista --}}
            <div style="max-height: 300px; overflow-y: auto;">
                @forelse($notifications as $notification)
                    <a
                        href="{{ $notification->data['url'] ?? '#' }}"
                        target="_blank"
                        wire:click.prevent="openNotification('{{ $notification->id }}')"
                        class="dropdown-item px-3 py-2"
                    >
                        <div class="d-flex align-items-start">
                            
                            {{-- Ícone --}}
                            <div class="mr-3">
                                @php
                                    $color = $notification->data['color'] ?? 'secondary';

                                    $icon = match($notification->data['type'] ?? '') {
                                        'invoice_paid' => 'fas fa-money-bill-wave',
                                        'company_created' => 'fas fa-building',
                                        'reservation_created' => 'fas fa-calendar-check',
                                        'support_ticket' => 'fas fa-life-ring',
                                        default => 'fas fa-bell',
                                    };
                                @endphp

                                <span class="badge badge-{{ $color }} p-2">
                                    <i class="{{ $icon }}"></i>
                                </span>
                            </div>

                            {{-- Conteúdo --}}
                            <div class="flex-grow-1">
                                <p class="mb-1 text-sm font-weight-bold text-dark">
                                    {{ $notification->data['title'] ?? 'Nova notificação' }}
                                </p>

                                <small class="text-muted d-block">
                                    {{ $notification->data['message'] ?? '' }}
                                </small>

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
                        </div>
                    </a>

                    <div class="dropdown-divider"></div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="far fa-bell-slash fa-2x mb-2"></i>
                        <p class="mb-0">Nenhuma notificação</p>
                    </div>
                @endforelse
            </div>

            {{-- Rodapé --}}
            <a href="{{ route('notifications.index') }}"
               class="dropdown-item dropdown-footer text-center font-weight-bold bg-light">
                Ver todas as notificações
            </a>
        </div>
    </li>
</div>
