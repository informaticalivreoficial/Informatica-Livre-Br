<div>
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-search mr-2"></i> Categorias</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Categorias</li>
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
                    <a @click="$dispatch('open-category-modal', { editId: null, categoryId: null })"
                        class="btn btn-sm btn-default">
                        <i class="fas fa-plus mr-2"></i> 
                        Cadastrar Novo
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if($categories->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped projects">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th class="text-center">Exibir?</th>
                                <th class="text-center">Criado em</th>
                                <th class="text-center">Tipo</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td><i class="fas fa-angle-right"></i> {{$category->title}}</td>
                                    <td class="text-center">{{ $category->status ? 'Sim' : 'Não' }}</td>
                                    <td class="text-center">{{date('d/m/Y H:i', strtotime($category->created_at))}}</td>
                                    <td class="text-center">{{$category->type}}</td>
                                    <td class="px-4 py-4 flex items-center justify-center gap-2 h-full">
                                        <!-- Editar categoria -->
                                        <a 
                                            data-id="{{ $category->id }}"
                                            x-on:click="$dispatch('open-category-modal', { editId: parseInt($el.dataset.id) })"
                                            class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:text-blue-800 transition flex-shrink-0">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        <!-- Criar subcategoria -->
                                        <a 
                                            data-parent-id="{{ $category->id }}"
                                            x-on:click="$dispatch('open-category-modal', { categoryId: parseInt($el.dataset.parentId) })" 
                                           class="btn btn-sm btn-success">
                                           Criar Subcategoria
                                        </a>  
                                        <button type="button" class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-800 transition flex-shrink-0" wire:click="setDeleteId({{$category->id}})">
                                            <i class="fas fa-trash"></i>
                                        </button>                                 
                                    </td>
                                </tr>
                                @if ($category->children)
                                    @foreach($category->children as $subcategory)                        
                                    <tr>                            
                                        <td><i class="fas fa-angle-double-right"></i>  {{$subcategory->title}}</td>
                                        <td class="text-center">{{ $subcategory->status ? 'Sim' : 'Não' }}</td>
                                        <td class="text-center">{{$subcategory->created_at}}</td>
                                        <td class="text-center">---------</td>
                                        <td class="px-4 py-4 flex items-center justify-center gap-2 h-full">
                                            <a 
                                                data-edit-id="{{ $subcategory->id }}"
                                                x-on:click="$dispatch('open-category-modal', { editId: parseInt($el.dataset.editId) })" 
                                                class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:text-blue-800 transition flex-shrink-0"><i class="fas fa-pen"></i></a>
                                            <button wire:click="setDeleteId({{$subcategory->id}})" type="button" class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-800 transition flex-shrink-0">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table> 
                </div>               
                <div class="mt-3">
                    {{$categories->links()}}
                </div>         
            @else
                <div class="alert alert-info mb-0">
                    Nenhum registro encontrado!
                </div>
            @endif 
            
            <div 
                x-data="{ open: false }"
                x-on:open-category-modal.window="
                    open = true;
                    Livewire.dispatch('loadCategory', { payload: $event.detail })
                "
                x-on:category-saved.window="open = false"
                x-show="open"
                style="display: none"
                class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-[1050]"
            >
                <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6">
                    <livewire:dashboard.posts.cat-post-form />
                    <div class="mt-4 text-right">
                        <button 
                        @click="
                            open = false;
                            Livewire.dispatch('resetForm')
                        " 
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>

            
        </div>
    </div>

   
</div>

<script>    
    document.addEventListener('livewire:initialized', () => {
        @this.on('swal', (event) => {
            const data = event
            swal.fire({
                icon:data[0]['icon'],
                title:data[0]['title'],
                text:data[0]['text'],
            })
        })

        @this.on('delete-prompt', (event) => {
            swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Você tem certeza que deseja excluir esta Categoria?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir!',
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.dispatch('goOn-Delete')
                }
            })
        })
    });
</script>
