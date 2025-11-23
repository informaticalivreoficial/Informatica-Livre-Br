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
            <div class="row">
                <div class="col-12">                
                    @if(session()->exists('message'))
                        @message(['color' => session()->get('color')])
                            {{ session()->get('mensagem') }}
                        @endmessage
                    @endif
                </div>            
            </div>
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
                                $expiredAt = \Carbon\Carbon::createFromFormat('d/m/Y', $slide->expired_at);
                                $diffInDays = now()->diffInDays($expiredAt, false);
                            @endphp
                            <tr class="border-t border-gray-200 hover:bg-gray-50 {{ $slide->status ? '' : 'bg-yellow-50' }}">
                                <!-- Imagem -->
                                <td class="px-4 py-2 text-center">
                                    <img 
                                        src="{{ url($slide->getimagem()) }}" 
                                        alt="{{ $slide->title }}" 
                                        class="w-32 mx-auto cursor-pointer rounded-lg hover:scale-105 transition-transform"
                                        @click="showModal = true; imageUrl = '{{ addslashes(url($slide->getimagem())) }}'">
                                </td>

                                <!-- Título -->
                                <td class="px-4 py-2">{{ $slide->title }}</td>

                                <!-- Expiração -->
                                <td class="px-4 py-2 text-center">
                                    @if ($diffInDays < 0)
                                        <span class="px-2 py-1 rounded text-white bg-red-600">
                                            Expirado ({{ $slide->expired_at }})
                                        </span>
                                    @elseif ($diffInDays <= 30)
                                        <span class="px-2 py-1 rounded text-black bg-yellow-300">
                                            Expira em {{ $diffInDays }} dias ({{ $slide->expired_at }})
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded text-white bg-green-600">
                                            {{ $slide->expired_at }}
                                        </span>
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
                                    <!-- Switch -->
                                    <label class="switch flex-shrink-0">
                                        <input type="checkbox" 
                                            value="{{ $slide->status }}"  
                                            wire:change="toggleStatus({{ $slide->id }})" 
                                            wire:loading.attr="disabled" 
                                            {{ $slide->status ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>

                                    <!-- Edit -->
                                    <a wire:navigate href="{{ route('slides.edit', $slide->id) }}" 
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 hover:text-blue-800 transition flex-shrink-0">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <!-- Delete -->
                                    <button type="button" 
                                            class="flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-800 transition flex-shrink-0"
                                            wire:click="setDeleteId({{ $slide->id }})" 
                                            title="Excluir">
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
