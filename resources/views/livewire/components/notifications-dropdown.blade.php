<div wire:poll.30s="loadNotifications" x-data="{ open: false }" class="relative">

    {{-- Sino --}}
    <button
        @click="open = !open"
        class="relative w-10 h-10 flex items-center justify-center rounded-lg hover:bg-slate-100 transition"
    >
        <i class="far fa-bell text-lg text-slate-600"></i>

        @if($unreadNotificationsCount > 0)
            <span
                class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center">
                {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div
        x-show="open"
        @click.outside="open = false"
        x-transition
        class="absolute right-0 mt-3 w-[420px] bg-white rounded-xl shadow-xl border border-slate-200 overflow-hidden z-50"
        style="display: none;"
    >

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border-b border-slate-200">

            <div>
                <h3 class="text-sm font-semibold text-slate-800">
                    🔔 Notificações
                </h3>

                <p class="text-xs text-slate-500">
                    {{ $unreadNotificationsCount }}
                    {{ Str::plural('notificação', $unreadNotificationsCount) }}
                </p>
            </div>

            @if($unreadNotificationsCount > 0)
                <button
                    wire:click="markAllAsRead"
                    class="text-green-600 hover:text-green-700 transition"
                    title="Marcar todas como lidas"
                >
                    <i class="fas fa-check-double"></i>
                </button>
            @endif

        </div>

        {{-- Lista (MANTIDA DO SEU CÓDIGO 👇) --}}
        <div class="max-h-[380px] overflow-y-auto notifications-scroll">

            @forelse($notifications as $notification)

                @php
                    $icon = match($notification->data['type'] ?? '') {
                        'invoice_paid' => 'fas fa-money-bill-wave',
                        'company_created' => 'fas fa-building',
                        'reservation_created' => 'fas fa-calendar-check',
                        'support_ticket' => 'fas fa-life-ring',
                        'ArticleCreated' => 'fas fa-file-alt',
                        default => 'fas fa-bell',
                    };

                    $color = match($notification->data['color'] ?? '') {
                        'success' => 'bg-green-100 text-green-600',
                        'danger' => 'bg-red-100 text-red-600',
                        'warning' => 'bg-yellow-100 text-yellow-600',
                        'info' => 'bg-blue-100 text-blue-600',
                        default => 'bg-slate-100 text-slate-600',
                    };
                @endphp

                <a
                    href="{{ $notification->data['url'] ?? '#' }}"
                    target="_blank"
                    wire:click.prevent="openNotification('{{ $notification->id }}')"
                    class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 transition border-b border-slate-100"
                >

                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $color }}">
                            <i class="{{ $icon }}"></i>
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-800 text-sm">
                            {{ $notification->data['title'] ?? 'Nova notificação' }}
                        </p>

                        @if(!empty($notification->data['message']))
                            <p class="text-sm text-slate-600 mt-1">
                                {{ $notification->data['message'] }}
                            </p>
                        @endif

                        <div class="flex items-center mt-2 text-xs text-slate-400">
                            <i class="far fa-clock mr-1"></i>
                            {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>

                    @if(is_null($notification->read_at))
                        <span class="w-2 h-2 bg-blue-500 rounded-full mt-2"></span>
                    @endif

                </a>

            @empty

                <div class="py-10 text-center text-slate-500">
                    <i class="far fa-bell-slash text-4xl text-slate-300"></i>
                    <p class="mt-2 text-sm">Nenhuma notificação</p>
                </div>

            @endforelse

        </div>

        {{-- Footer --}}
        <div class="border-t border-slate-200 bg-slate-50">
            <a
                href="{{ route('notifications.index') }}"
                class="block text-center py-3 text-sm font-semibold text-blue-600 hover:bg-slate-100 transition"
            >
                Ver todas as notificações
            </a>
        </div>

    </div>
</div>