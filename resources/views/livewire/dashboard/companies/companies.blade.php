<div>    
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-search mr-2"></i> Empresas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Empresas</li>
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
                    <a href="{{route('companies.create')}}" class="btn btn-sm btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Novo</a>
                </div>
            </div>
        </div>        
       
        <div class="card-body">
            @if(!empty($companies) && $companies->count() > 0)
                <div class="overflow-x-auto" x-data="{ showModal: false, imageUrl: '' }">
                    <table class="table table-bordered table-striped projects">
                        <thead>
                            <tr>
                                <th>Logomarca</th>
                                <th wire:click="sortBy('alias_name')">Nome Fantasia <i class="expandable-table-caret fas fa-caret-down fa-fw"></i></th>
                                <th class="text-center">Faturas</th>
                                <th class="text-center">Responsável</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companies as $company)                    
                            <tr style="{{ ($company->status == true ? '' : 'background: #fffed8 !important;')  }}">                            
                                <td>
                                    <img 
                                        src="{{$company->getlogo()}}" 
                                        alt="{{$company->alias_name}}" 
                                        title="{{$company->alias_name}}"
                                        class="w-12 mx-auto cursor-pointer rounded-lg hover:scale-105 transition-transform"
                                        @click="showModal = true; imageUrl = '{{ addslashes(url($company->getlogo())) }}'"
                                    />
                                </td>
                                <td>{{$company->alias_name}}</td>
                                <td class="text-center">{{$company->invoices()->count()}}</td>
                                <td class="text-center"></td>
                                <td>  
                                    <div class="flex items-center justify-center gap-1">                              
                                        <x-forms.switch-toggle
                                            wire:key="safe-switch-{{ $company->id }}"
                                            wire:click="toggleStatus({{ $company->id }})"
                                            :checked="$company->status"
                                            size="sm"
                                            color="green"
                                        />
                                        <button type="button" 
                                                class="btn btn-xs bg-info"
                                                wire:click="viewCompany({{ $company->id }})"
                                                title="Visualizar"
                                        >
                                            <i class="fas fa-search"></i>
                                        </button>                                  
                                        <a href="{{route('companies.edit',['company' => $company->id])}}" class="btn btn-xs btn-default"><i class="fas fa-pen"></i></a>
                                        @if (auth()->user()->isSuperAdmin())
                                            <button type="button" 
                                                    class="btn btn-xs bg-danger"
                                                    wire:click="setDeleteId({{ $company->id }})"
                                                    title="Excluir"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
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
            {{ $companies->links() }}  
        </div>
    </div>

    @if($showCompanyModal && $companySelected)
    <div 
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999]"
        x-data
        x-init="@this.on('close-modal', () => $el.remove())"
    >
        <div class="bg-white w-[600px] rounded-lg shadow-lg p-6 relative animate-fade">

            <!-- Botão fechar -->
            <button 
                class="absolute top-2 right-2 text-xl"
                wire:click="$set('showCompanyModal', false)">
                ✕
            </button>

            <h2 class="text-xl font-bold mb-4">Detalhes da Empresa</h2>

            <div class="space-y-2">
                <p><strong>Nome Fantasia:</strong> {{ $companySelected->alias_name }}</p>
                <p><strong>Razão Social:</strong> {{ $companySelected->corporate_name }}</p>
                <p><strong>CNPJ:</strong> {{ $companySelected->cnpj }}</p>
                <p><strong>Status:</strong> {{ $companySelected->status ? 'Ativa' : 'Inativa' }}</p>

                <p><strong>Ocorrências:</strong> {{ $companySelected->ocorrencias->count() }}</p>
                <p><strong>Colaboradores:</strong> {{ $companySelected->users->count() }}</p>
            </div>

            <div class="mt-4 text-right">
                <button 
                    class="px-4 py-2 bg-gray-600 text-white rounded"
                    wire:click="$set('showCompanyModal', false)">
                    Fechar
                </button>
            </div>

        </div>
    </div>
    @endif

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
                text: 'Você tem certeza que deseja excluir esta Empresa?',
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