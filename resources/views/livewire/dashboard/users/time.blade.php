<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-users mr-2"></i> Time de Usuários</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Time de Usuários</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                <i class="fas fa-users text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['total'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ativos</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['active'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                <i class="fas fa-times-circle text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Inativos</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['inactive'] }}</p>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="Buscar por nome ou email..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
            </div>
            <a
                href="{{ route('users.create') }}"
                class="inline-flex items-center justify-center bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition shrink-0"
            >
                <i class="fas fa-plus mr-2"></i> Cadastrar Novo
            </a>
        </div>

        <div class="p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse($users as $user)
                    @php
                        if (!empty($user->avatar) && \Illuminate\Support\Facades\Storage::disk()->exists($user->avatar)) {
                            $cover = \Illuminate\Support\Facades\Storage::url($user->avatar);
                        } else {
                            if ($user->gender == 'masculino') {
                                $cover = url(asset('theme/images/avatar5.png'));
                            } elseif ($user->gender == 'feminino') {
                                $cover = url(asset('theme/images/avatar3.png'));
                            } else {
                                $cover = url(asset('theme/images/image.jpg'));
                            }
                        }

                        $roleColors = [
                            'super-admin' => 'bg-purple-50 text-purple-700',
                            'manager' => 'bg-blue-50 text-blue-700',
                            'employee' => 'bg-teal-50 text-teal-700',
                        ];
                    @endphp
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden {{ $user->status ? '' : 'bg-yellow-50/60' }}">
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <img src="{{ $cover }}" alt="{{ $user->name }}" class="w-14 h-14 rounded-full object-cover shrink-0">
                                    <div class="min-w-0">
                                        <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $user->name }}</h3>
                                        <div class="flex flex-wrap gap-1.5 mt-1.5">
                                            @forelse($user->roles as $role)
                                                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-medium {{ $roleColors[$role->name] ?? 'bg-gray-100 text-gray-600' }}">
                                                    {{ $role->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400">Sem cargo</span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                <x-forms.switch-toggle
                                    wire:key="time-switch-{{ $user->id }}"
                                    wire:click="toggleStatus({{ $user->id }})"
                                    :checked="$user->status"
                                    size="sm"
                                    color="green"
                                />
                            </div>

                            <div class="mt-4 space-y-1.5 text-sm text-gray-500">
                                <p class="flex items-center gap-2 truncate">
                                    <i class="fas fa-envelope w-4 text-gray-400 shrink-0"></i>
                                    <span class="truncate">{{ $user->email }}</span>
                                </p>
                                @if($user->cell_phone)
                                    <p class="flex items-center gap-2">
                                        <i class="fas fa-phone w-4 text-gray-400 shrink-0"></i>
                                        {{ $user->cell_phone }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-1.5">
                            @if($user->whatsapp != '')
                                <a target="_blank" href="{{ \App\Helpers\WhatsApp::getNumZap($user->whatsapp) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition" title="WhatsApp">
                                    <i class="fab fa-whatsapp text-xs"></i>
                                </a>
                            @endif
                            <button type="button" wire:click="$dispatch('open-email-modal', { recipient: '{{ $user->email }}', recipientName: '{{ $user->name }}' })" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-600 hover:text-white transition" title="Enviar Email">
                                <i class="fas fa-envelope text-xs"></i>
                            </button>
                            <a wire:navigate href="{{ route('users.view', $user->id) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition" title="Visualizar">
                                <i class="fas fa-search text-xs"></i>
                            </a>
                            <a wire:navigate href="{{ route('users.edit', ['userId' => $user->id]) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition" title="Editar">
                                <i class="fas fa-pen text-xs"></i>
                            </a>
                            <button type="button" wire:click="setDeleteId({{ $user->id }})" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition" title="Excluir">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center">
                        <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                        <p class="text-sm text-gray-400">Nenhum usuário encontrado</p>
                        <a href="{{ route('users.create') }}" class="inline-block mt-3 text-sm text-teal-600 hover:text-teal-700 font-medium">
                            <i class="fas fa-plus mr-1"></i> Cadastrar o primeiro usuário
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        @if ($users->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>