<div>
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-search mr-2"></i> Posts</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Posts</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12 col-sm-6 my-2">
                    <div class="card-tools">
                        <div style="width: 250px;">
                            <form class="input-group input-group-sm" action="" method="post">
                                <input type="text" wire:model.live="search" class="form-control float-right" placeholder="Pesquisar">               
                                
                            </form>
                        </div>
                      </div>
                </div>
                <div class="col-12 col-sm-6 my-2 text-right">
                    <a wire:navigate href="{{route('posts.create')}}" class="btn btn-sm btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Novo</a>
                </div>
            </div>
        </div>

        <div class="card-body"> 
            @if ($posts->count())
                <div class="overflow-x-auto" x-data="{ showModal: false, imageUrl: '' }">
                    <table class="table-auto w-full border-collapse border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2">Capa</th>
                                <th class="px-4 py-2 cursor-pointer" wire:click="sortBy('title')">
                                    Título <i class="fas fa-caret-down fa-fw ml-1"></i>
                                </th>
                                <th class="px-4 py-2 text-center">Categoria</th>
                                <th class="px-4 py-2 text-center">Views</th>
                                <th class="px-4 py-2 text-center">Imagens</th>
                                <th class="px-4 py-2 text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                            <tr class="border-t border-gray-200 hover:bg-gray-50 {{ $post->status ? '' : 'bg-yellow-100' }}">
                                <!-- Imagem -->
                                <td class="px-4 py-2 text-center">
                                    <img 
                                        src="{{ url($post->cover()) }}" 
                                        alt="{{ $post->title }}" 
                                        class="w-16 mx-auto cursor-pointer rounded-lg hover:scale-105 transition-transform"
                                        @click="showModal = true; imageUrl = '{{ addslashes(url($post->nocover())) }}'">
                                </td>
                                <td class="px-4 py-2">{{ $post->title }}</td>
                                <td class="px-4 py-2 text-center">
                                    {{ $post->category()->first() ? $post->category()->first()->title : 'N/D' }}
                                </td>
                                <td class="px-4 py-2 text-center">{{ $post->views }}</td>
                                <td class="px-4 py-2 text-center">{{ $post->countimages() ? $post->countimages() : 0 }}</td>
                                
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Switch -->
                                        <label class="switch flex-shrink-0">
                                            <input type="checkbox" 
                                                wire:click="toggleStatus({{ $post->id }})" 
                                                wire:loading.attr="disabled" 
                                                {{ $post->status ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>

                                        <!-- View -->
                                        <a href="{{-- route('posts.show', $post->id) --}}" 
                                            class="action-btn btn-view" 
                                            data-tooltip="Visualizar">
                                            <i class="fas fa-search"></i>
                                        </a>

                                        <!-- Edit -->
                                        <a href="{{ route('posts.edit', $post->id) }}" 
                                            class="action-btn btn-edit" 
                                            data-tooltip="Editar">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        <!-- Delete -->
                                        <button type="button" 
                                            class="action-btn btn-delete" 
                                            data-tooltip="Excluir"
                                            wire:click="setDeleteId({{ $post->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Modal de imagem -->
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
                    <div class="text-center mt-4">
                        <button wire:click="loadMore" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Carregar mais
                        </button>
                    </div>
                @endif
            @else
                <div class="row mb-4">
                    <div class="col-12">                                                        
                        <div class="alert alert-info p-3">
                            Não foram encontrados registros!
                        </div>                                                        
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        Livewire.on('swal', (data) => {
            Swal.fire({
                icon: data[0].icon,
                title: data[0].title,
                text: data[0].text,
            });
        });

        Livewire.on('delete-prompt', () => {
            Swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Você tem certeza que deseja excluir este post?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir!',
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('goOn-Delete');
                }
            });
        });
    });
</script>