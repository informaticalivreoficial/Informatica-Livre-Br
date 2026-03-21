<div>
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-search mr-2"></i> Banners</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Banners</li>
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
                    <a wire:navigate href="{{route('slides.create')}}" class="btn btn-sm btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Novo</a>
                </div>
            </div>
        </div>        
        <!-- /.card-header -->
        <div class="card-body">            
            @if($slides->count() > 0)
                <div class="overflow-x-auto" x-data="{ showModal: false, imageUrl: '' }">
                    <table class="table-auto w-full border-collapse border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2">Imagem</th>
                                <th class="px-4 py-2 cursor-pointer" wire:click="sortBy('title')">
                                    Título <i class="fas fa-caret-down fa-fw ml-1"></i>
                                </th>
                                <th class="px-4 py-2 text-center">Expira</th>
                                <th class="px-4 py-2 text-center">Link</th>
                                <th class="px-4 py-2 text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($slides as $slide)
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
                            <tr class="border-t border-gray-200 hover:bg-gray-50 {{ $slide->status ? '' : 'bg-yellow-50' }}">
                                <!-- Imagem -->
                                <td class="px-4 py-2 text-center">
                                    <img 
                                        src="{{ $slide->getimagem() }}" 
                                        alt="{{ $slide->title }}" 
                                        class="w-32 mx-auto cursor-pointer rounded-lg hover:scale-105 transition-transform"
                                        @click="showModal = true; imageUrl = '{{ addslashes($slide->getimagem()) }}'">
                                </td>

                                <!-- Título -->
                                <td class="px-4 py-2">{{ $slide->title }}</td>

                                <!-- Expiração -->
                                <td class="px-4 py-2 text-center">
                                    @if(!$expiredAt)
                                        <span class="px-2 py-1 rounded text-white bg-gray-400">Sem expiração</span>
                                    @elseif($diffInDays < 0)
                                        {{-- expirado --}}
                                    @elseif($diffInDays <= 30)
                                        {{-- expira em breve --}}
                                    @else
                                        {{-- ok --}}
                                    @endif
                                </td>

                                <!-- Link -->
                                <td class="px-4 py-2 text-center">
                                    @if(!empty($slide->link))
                                        <a href="{{ $slide->link }}" target="_blank" class="text-green-600 hover:text-green-800">
                                            <i class="fa fa-link"></i>
                                        </a>
                                    @else
                                        <span class="text-red-600">
                                            <i class="fa fa-xmark"></i>
                                        </span>
                                    @endif
                                </td>

                                <!-- Ações -->
                                <td class="px-4 py-4 flex items-center justify-center gap-2 h-full">
                                    <x-forms.switch-toggle
                                        wire:key="safe-switch-{{ $slide->id }}"
                                        wire:click="toggleStatus({{ $slide->id }})"
                                        :checked="$slide->status"
                                        size="sm"
                                        color="green"
                                    />
                                    <a href="{{ route('slides.edit', $slide->id) }}" 
                                        class="btn btn-xs btn-default" 
                                        title="Editar">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button type="button" 
                                        class="btn btn-xs bg-danger text-white" 
                                        title="Excluir Empresa"
                                        wire:click="setDeleteId({{ $slide->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
        <div class="card-footer clearfix">  
            {{ $slides->links() }}  
        </div>
    </div>

</div>
