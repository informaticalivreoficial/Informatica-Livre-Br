<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-users mr-2"></i> Clientes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Clientes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
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
                <i class="fas fa-plus mr-2"></i> Cadastrar Cliente
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Foto</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide cursor-pointer" wire:click="sortBy('name')">
                            Nome <i class="fas fa-caret-down fa-fw ml-1"></i>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">CPF</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                        @php
                            if(!empty($user->avatar) && \Illuminate\Support\Facades\Storage::exists($user->avatar)){
                                $cover = \Illuminate\Support\Facades\Storage::url($user->avatar);
                            } else {
                                if($user->gender == 'masculino'){
                                    $cover = url(asset('theme/images/avatar5.png'));
                                }elseif($user->gender == 'feminino'){
                                    $cover = url(asset('theme/images/avatar3.png'));
                                }else{
                                    $cover = url(asset('theme/images/image.jpg'));
                                }
                            }
                        @endphp
                        <tr class="hover:bg-gray-50 transition {{ $user->status ? '' : 'bg-yellow-50' }}">
                            <td class="px-4 py-3">
                                <a href="{{ url($cover) }}" data-title="{{ $user->name }}" data-toggle="lightbox">
                                    <img alt="{{ $user->name }}" src="{{ url($cover) }}" class="w-10 h-10 object-cover rounded-full">
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $user->cpf }}</td>
                            <td class="px-4 py-3 text-center">
                                <x-forms.switch-toggle
                                    wire:key="safe-switch-{{ $user->id }}"
                                    wire:click="toggleStatus({{ $user->id }})"
                                    :checked="$user->status"
                                    size="sm"
                                    color="green"
                                />
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($user->whatsapp != '')
                                        <a target="_blank" href="{{ \App\Helpers\WhatsApp::getNumZap($user->whatsapp) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition" title="WhatsApp">
                                            <i class="fab fa-whatsapp text-xs"></i>
                                        </a>
                                    @endif
                                    <button type="button" wire:click="$dispatch('open-email-modal', { recipient: '{{ $user->email }}', recipientName: '{{ $user->name }}' })" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-600 hover:text-white transition" title="Enviar Email">
                                        <i class="fas fa-envelope text-xs"></i>
                                    </button>
                                    <a wire:navigate href="visualizar/{{ $user->id }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition" title="Visualizar">
                                        <i class="fas fa-search text-xs"></i>
                                    </a>
                                    <a wire:navigate href="{{ route('users.edit', [ 'userId' => $user->id ]) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition" title="Editar">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    <button type="button" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition" title="Excluir" wire:click="setDeleteId({{ $user->id }})">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-400">Nenhum cliente encontrado</p>
                                <a href="{{ route('users.create') }}" class="inline-block mt-3 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                    <i class="fas fa-plus mr-1"></i> Cadastrar o primeiro cliente
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>