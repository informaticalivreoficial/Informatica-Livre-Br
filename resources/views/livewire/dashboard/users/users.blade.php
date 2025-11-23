<div>     
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-search mr-2"></i> Clientes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Clientes</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>   {{-- 
    @if ($updateMode)
        @livewire('dashboard.users.form')
    @endif  --}} 
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
                    <a wire:navigate href="cadastrar-cliente" class="btn btn-sm btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Novo</a>
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
            @if(!empty($users) && $users->count() > 0)
                <table class="table table-bordered table-striped projects">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th wire:click="sortBy('name')">Nome <i class="expandable-table-caret fas fa-caret-down fa-fw"></i></th>
                            <th>CPF</th>
                            <th class="text-center">Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)                    
                        <tr style="{{ ($user->status == true ? '' : 'background: #fffed8 !important;')  }}">
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
                            <td class="text-center">
                                <a href="{{url($cover)}}" data-title="{{$user->name}}" data-toggle="lightbox">
                                    <img alt="{{$user->name}}" class="table-avatar" src="{{url($cover)}}">
                                </a>
                            </td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->cpf}}</td>
                            <td class="text-center">
                                <label class="switch" wire:model="active">
                                    <input type="checkbox" value="{{$user->status}}"  wire:change="toggleStatus({{$user->id}})" wire:loading.attr="disabled" {{$user->status ? 'checked': ''}}>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                
                                @if($user->whatsapp != '')
                                    <a target="_blank" href="{{\App\Helpers\WhatsApp::getNumZap($user->whatsapp)}}" class="btn btn-xs btn-success text-white"><i class="fab fa-whatsapp"></i></a>
                                @endif
                                
                                <form class="btn btn-xs" action="{{--route('email.send')--}}" method="post">
                                    @csrf
                                    <input type="hidden" name="nome" value="{{ $user->name }}">
                                    <input type="hidden" name="email" value="{{ $user->email }}">
                                    <button title="Enviar Email" type="submit" class="btn btn-xs text-white bg-teal"><i class="fas fa-envelope"></i></button>
                                </form> 
                                <a wire:navigate href="visualizar/{{$user->id}}" class="btn btn-xs btn-info text-white"><i class="fas fa-search"></i></a>
                                <a wire:navigate href="{{ route('users.edit', [ 'userId' => $user->id ]) }}" class="btn btn-xs btn-default"><i class="fas fa-pen"></i></a>
                                <button type="button" class="btn btn-xs btn-danger text-white" wire:click="setDeleteId({{$user->id}})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>                
                </table>
            @else
                <div class="row">
                    <div class="col-12">                                                        
                        <div class="alert alert-info p-3">
                            Não foram encontrados registros!
                        </div>                                                        
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer clearfix">  
            {{ $users->links() }}  
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
                text: 'Você tem certeza que deseja excluir este Cliente?',
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