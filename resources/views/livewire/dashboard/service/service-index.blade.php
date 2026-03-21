<div>
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-store mr-2"></i> Serviços</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Serviços</li>
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
                    <a
                        href="{{ route('services.create') }}"
                        class="btn btn-sm btn-default"
                    >
                        <i class="fas fa-plus mr-2"></i> Cadastrar
                </a>
                </div>
            </div>
        </div>        

        <div class="card-body p-0">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th wire:click="sortBy('name')" style="cursor:pointer">
                            Serviço
                        </th>
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Intervalo</th>
                        <th>Status</th>
                        <th width="120">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td>
                                <strong>{{ $service->name }}</strong>
                                @if($service->description)
                                    <br>
                                    <small class="text-muted">
                                        {{ Str::limit($service->description, 60) }}
                                    </small>
                                @endif
                            </td>

                            <td>
                                {{ $service->category?->name ?? '-' }}
                            </td>

                            <td>
                                @if($service->billing_type === 'recurring')
                                    <span class="badge badge-info">
                                        Recorrente
                                    </span>
                                @else
                                    <span class="badge badge-secondary">
                                        Freela
                                    </span>
                                @endif
                            </td>

                            <td>
                                R$ {{ number_format($service->price, 2, ',', '.') }}
                            </td>

                            <td>
                                {{ $service->interval?->label() ?? '-' }}
                            </td>

                            

                            <td>
                                @if($service->status == true)
                                    <span class="badge badge-success">Ativo</span>
                                @else
                                    <span class="badge badge-secondary">Inativo</span>
                                @endif
                            </td>

                            <td class="text-right">
                                <a href="{{ route('services.edit', $service) }}"
                                   class="btn btn-xs btn-default"
                                   title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <button
                                    class="btn btn-xs bg-danger"
                                    wire:click="confirmDelete({{ $service->id }})"
                                    title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Nenhum serviço cadastrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer clearfix">
            {{ $services->links() }}
        </div>
    </div>
</div>
