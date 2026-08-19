<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-newspaper mr-2"></i> Posts</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Posts</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fas fa-newspaper text-lg"></i>
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
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Publicados</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['active'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center shrink-0">
                <i class="fas fa-pen text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Rascunhos</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['draft'] }}</p>
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
                    placeholder="Buscar por título, conteúdo, slug ou categoria..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
            </div>
            <a
                wire:navigate
                href="{{ route('posts.create') }}"
                class="inline-flex items-center justify-center bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition shrink-0"
            >
                <i class="fas fa-plus mr-2"></i> Cadastrar Post
            </a>
        </div>

        <div class="overflow-x-auto" x-data="{ showModal: false, imageUrl: '' }">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Capa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide cursor-pointer" wire:click="sortBy('title')">
                            Título <i class="fas fa-caret-down fa-fw ml-1"></i>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Categoria</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Views</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Imagens</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($posts as $post)
                        <tr class="hover:bg-gray-50 transition {{ $post->status ? '' : 'bg-yellow-50' }}">
                            <td class="px-4 py-3">
                                <img
                                    src="{{ url($post->cover()) }}"
                                    alt="{{ $post->title }}"
                                    class="w-14 h-14 object-cover rounded-lg cursor-pointer hover:scale-105 transition-transform"
                                    @click="showModal = true; imageUrl = '{{ addslashes(url($post->nocover())) }}'"
                                >
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $post->title }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 text-center">{{ $post->category()->first() ? $post->category()->first()->title : 'N/D' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 text-center">{{ $post->views }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 text-center">{{ $post->countimages() ? $post->countimages() : 0 }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <x-forms.switch-toggle
                                        wire:key="safe-switch-{{ $post->id }}"
                                        wire:click="toggleStatus({{ $post->id }})"
                                        :checked="$post->status"
                                        size="sm"
                                        color="green"
                                    />
                                    <a target="_blank" href="{{ route('web.' . (
                                                $post->type == 'artigo' ? 'blog.artigo' : (
                                                $post->type == 'noticia' ? 'blog.noticia' : 'page')), $post->slug) }}"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition"
                                        title="Visualizar">
                                        <i class="fas fa-search text-xs"></i>
                                    </a>
                                    <a href="{{ route('posts.edit', $post->id) }}"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition"
                                        title="Editar Post">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    <button type="button"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition"
                                        title="Excluir Post"
                                        wire:click="setDeleteId({{ $post->id }})">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-400">Nenhum post encontrado</p>
                                <a wire:navigate href="{{ route('posts.create') }}" class="inline-block mt-3 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                    <i class="fas fa-plus mr-1"></i> Cadastrar o primeiro post
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

        @if($posts->hasMorePages())
            <div class="px-4 py-3 border-t border-gray-100 text-center">
                <button wire:click="loadMore" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-lg text-sm font-medium hover:bg-teal-700 transition">
                    <i class="fas fa-plus text-xs"></i> Carregar mais
                </button>
            </div>
        @endif
    </div>
</div>