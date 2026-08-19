<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-images mr-2"></i> Banners</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Banners</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fas fa-images text-lg"></i>
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
            <div class="w-11 h-11 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center shrink-0">
                <i class="fas fa-eye-slash text-lg"></i>
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
                    placeholder="Buscar por título..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
            </div>
            <a
                wire:navigate
                href="{{ route('slides.create') }}"
                class="inline-flex items-center justify-center bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition shrink-0"
            >
                <i class="fas fa-plus mr-2"></i> Cadastrar Banner
            </a>
        </div>

        <div class="overflow-x-auto" x-data="{ showModal: false, imageUrl: '' }">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Imagem</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide cursor-pointer" wire:click="sortBy('title')">
                            Título <i class="fas fa-caret-down fa-fw ml-1"></i>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Expira</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Link</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($slides as $slide)
                        @php
                            try {
                                $expiredAt = $slide->expired_at
                                    ? \Carbon\Carbon::createFromFormat('d/m/Y', $slide->expired_at)
                                    : null;
                                $diffInDays = $expiredAt ? now()->diffInDays($expiredAt, false) : null;
                            } catch (\Exception $e) {
                                $expiredAt = null;
                                $diffInDays = null;
                            }
                        @endphp
                        <tr class="hover:bg-gray-50 transition {{ $slide->status ? '' : 'bg-yellow-50' }}">
                            <td class="px-4 py-3">
                                <img
                                    src="{{ $slide->getimagem() }}"
                                    alt="{{ $slide->title }}"
                                    class="w-32 h-16 object-cover rounded-lg cursor-pointer hover:scale-105 transition-transform"
                                    @click="showModal = true; imageUrl = '{{ addslashes($slide->getimagem()) }}'"
                                >
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $slide->title }}</td>
                            <td class="px-4 py-3 text-center">
                                @if(!$expiredAt)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Sem expiração</span>
                                @elseif($diffInDays < 0)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Expirado</span>
                                @elseif($diffInDays <= 30)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Expira em {{ $expiredAt->format('d/m/Y') }}</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">{{ $expiredAt->format('d/m/Y') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if(!empty($slide->link))
                                    <a href="{{ $slide->link }}" target="_blank" class="text-green-600 hover:text-green-800 transition">
                                        <i class="fa fa-link"></i>
                                    </a>
                                @else
                                    <span class="text-red-500">
                                        <i class="fa fa-xmark"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <x-forms.switch-toggle
                                        wire:key="safe-switch-{{ $slide->id }}"
                                        wire:click="toggleStatus({{ $slide->id }})"
                                        :checked="$slide->status"
                                        size="sm"
                                        color="green"
                                    />
                                    <a href="{{ route('slides.edit', $slide->id) }}"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition"
                                        title="Editar">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    <button type="button"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition"
                                        title="Excluir"
                                        wire:click="setDeleteId({{ $slide->id }})">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-400">Nenhum banner encontrado</p>
                                <a wire:navigate href="{{ route('slides.create') }}" class="inline-block mt-3 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                    <i class="fas fa-plus mr-1"></i> Cadastrar o primeiro banner
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div x-show="showModal" x-cloak
                class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-[9999]"
                x-transition>
                <div class="relative">
                    <img :src="imageUrl" class="max-w-[70vw] max-h-[70vh] object-contain mx-auto rounded shadow-lg">
                    <button type="button" @click="showModal = false"
                            class="absolute top-2 right-2 text-white text-xl bg-black bg-opacity-50 rounded-full px-2 py-1 hover:bg-opacity-75 transition">
                        ✕
                    </button>
                </div>
            </div>
        </div>

        @if ($slides->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $slides->links() }}
            </div>
        @endif
    </div>
</div>