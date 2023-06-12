@extends('adminlte::page')

@section('title', 'Gerenciar Pedidos')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1><i class="fas fa-search mr-2"></i> Pedidos</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">                    
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item active">Pedidos</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-12 col-sm-6 my-2">
                <div class="card-tools">
                    <div style="width: 250px;">
                        <form class="input-group input-group-sm" action="" method="post">
                            @csrf   
                            <input type="text" name="filter" value="{{ $filters['filter'] ?? '' }}" class="form-control float-right" placeholder="Pesquisar">
            
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                  </div>
            </div>
            <div class="col-12 col-sm-6 my-2 text-right">
                <a href="{{route('pedidos.create')}}" class="btn btn-sm btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Novo</a>
            </div>
        </div>
    </div>        
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-12">                
                @if(session()->exists('message'))
                    @message(['color' => session()->get('color')])
                        {{ session()->get('message') }}
                    @endmessage
                @endif
            </div>           
        </div>
        @if(!empty($pedidos) && $pedidos->count() > 0)
            <table class="table table-bordered table-striped projects">
                <thead>
                    <tr>
                        <th class="text-center">Empresa</th>                                                
                        <th class="text-center">Vencimento</th>                        
                        <th class="text-center">Valor</th>                        
                        <th class="text-center">Status</th>
                        <th class="text-center">Envio</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)                    
                    <tr>
                        <td>{{$pedido->getEmpresa->alias_name}}</td>                        
                        <td class="text-center">{{Carbon\Carbon::parse($pedido->vencimento)->format('d/m/Y')}}</td> 
                        <td class="text-center">R$ {{$pedido->valor ?? str_replace(',00', '', $pedido->itensTotalValor())}}</td>                       
                        <td class="text-center">{!!$pedido->getStatus()!!}</td> 
                        <td class="text-center">
                            @if ($pedido->form_sendat == null)
                                <a href="javascript:void(0)" class="btn btn-xs btn-success text-white j_enviaform cli{{ $pedido->id }}" data-id="{{ $pedido->id }}">Enviar Fatura <i class="fas fa-check"></i></a>
                            @else
                                <a href="javascript:void(0)" class="btn btn-xs btn-secondary text-white j_enviaform cli{{ $pedido->id }}" data-id="{{ $pedido->id }}">Reenviar Fatura <i class="fas fa-check"></i></a>
                            @endif                                
                        </td>
                        <td>
                            <a href="{{route('pedidos.show',['id' => $pedido->id])}}" class="btn btn-xs btn-info text-white"><i class="fas fa-search"></i></a>
                            <button type="button" class="btn btn-xs btn-danger text-white j_modal_btn" data-id="{{$pedido->id}}" data-toggle="modal" data-target="#modal-default">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>                
            </table>
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
    <div class="card-footer paginacao">
        @if (isset($filters))
            {{ $pedidos->appends($filters)->links() }}
        @else
            {{ $pedidos->links() }}
        @endif          
    </div>
    <!-- /.card-body -->
</div>
<!-- /.card -->

<div class="modal fade" id="modal-content">
    <div class="modal-dialog">
        <div class="modal-content">  
            <div class="modal-header">
                <h4 class="modal-title">Orçamento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span class="j_data"></span>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Sair</button>                    
            </div>            
        </div>       
    </div>
</div>


<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="frm" action="" method="post">            
            @csrf
            @method('DELETE')
            <input id="id_pedido" name="pedido_id" type="hidden" value=""/>
                <div class="modal-header">
                    <h4 class="modal-title">Remover Pedido!</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span class="j_param_data"></span>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Sair</button>
                    <button type="submit" class="btn btn-primary">Excluir Agora</button>
                </div>
            </form>
        </div>       
    </div>
</div>
@stop

@section('plugins.Toastr', true)

@section('css')
<link rel="stylesheet" href="{{url(asset('backend/plugins/ekko-lightbox/ekko-lightbox.css'))}}">
<link href="{{url(asset('backend/plugins/bootstrap-toggle/bootstrap-toggle.min.css'))}}" rel="stylesheet">
@stop

@section('js')
    <script src="{{url(asset('backend/plugins/ekko-lightbox/ekko-lightbox.min.js'))}}"></script>
    <script src="{{url(asset('backend/plugins/bootstrap-toggle/bootstrap-toggle.min.js'))}}"></script>
    <script>
       $(function () {           
           
           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
           
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
              event.preventDefault();
              $(this).ekkoLightbox({
                alwaysShowClose: true
              });
            });

            $('.j_modal_btn').click(function() {
                var pedido_id = $(this).data('id');                
                $.ajax({
                    type: 'GET',
                    dataType: 'JSON',
                    url: "{{ route('pedidos.delete') }}",
                    data: {
                       'id': pedido_id
                    },
                    success:function(data) {
                        if(data.error){
                            $('.j_param_data').html(data.error);
                            $('#id_pedido').val(data.id);
                            $('#frm').prop('action','{{ route('pedidos.deleteon') }}');
                        }else{
                            $('#frm').prop('action','{{ route('pedidos.deleteon') }}');
                        }
                    }
                });
            });

            $('.j_enviaform').click(function() {
                var id = $(this).data('id');                
                $.ajax({
                    type: 'GET',
                    dataType: 'JSON',
                    url: "{{ route('pedidos.sendFormFaturaClient') }}",
                    data: {
                       'id': id
                    },
                    beforeSend: function(){
                        $(".cli"+id).addClass('disabled')
                        $(".cli"+id).html("Carregando...");  
                    },
                    success:function(data) {
                        if(data.retorno == true){
                            $(".cli"+id).html("Reenviar Fatura <i class=\"fas fa-check\"></i>");
                        } else{
                            $(".cli"+id).html("Enviar Fatura <i class=\"fas fa-check\"></i>");
                        }
                    },
                    complete: function(resposta){
                        $(".cli"+id).removeClass('disabled');
                    }
                });
            });
            
            
        });
    </script>
@endsection