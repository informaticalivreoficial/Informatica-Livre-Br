@extends('adminlte::page')

@section('title', 'Gerenciar Listas')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1><i class="fas fa-suitcase mr-2"></i> Gerenciar Listas</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">                    
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item active">Gerenciar Listas</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-header text-right">
        <a href="{{route('listas.create')}}" class="btn btn-sm btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Lista</a>
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
        @if(!empty($listas) && $listas->count() > 0)
            <table class="table table-bordered table-striped projects">
                <thead>
                    <tr>
                        <th>Lista</th>
                        <th class="text-center">Emails</th>
                        <th class="text-center">Criado em:</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($listas as $lista)                    
                    <tr style="{{ ($lista->status == '1' ? '' : 'background: #fffed8 !important;')  }}">                            
                        <td><img src="{{url(asset('backend/assets/images/seta.png'))}}"> {{$lista->titulo}}</td>
                        <td class="text-center">{{$lista->newsletters->count()}}</td>
                        <td class="text-center">{{$lista->created_at}}</td>                           
                        <td class="acoes">
                            <a class="btn btn-secondary btn-xs" href=""><i class="fa fa-download"></i> Exportar csv</a>
                            <a class="btn btn-secondary btn-xs" href=""><i class="fa fa-download"></i> Exportar excel</a>
                            <input type="checkbox" data-onstyle="success" data-offstyle="warning" data-size="mini" class="toggle-class" data-id="{{ $lista->id }}" data-toggle="toggle" data-style="slow" data-on="<i class='fas fa-check'></i>" data-off="<i style='color:#fff !important;' class='fas fa-exclamation-triangle'></i>" {{ $lista->status == true ? 'checked' : ''}}>
                            <a data-toggle="tooltip" data-placement="top" title="Editar Lista" href="{{route('listas.edit',[ 'id' => $lista->id])}}" class="btn btn-xs btn-default"><i class="fas fa-pen"></i></a>
                            <a href="{{route('lista.newsletters',['categoria' => $lista->id])}}" class="btn btn-xs btn-info text-white"><i class="fas fa-search"></i></a>
                            <button data-placement="top" title="Remover Lista" type="button" class="btn btn-xs btn-danger text-white j_modal_btn" data-id="{{$lista->id}}" data-toggle="modal" data-target="#modal-default"><i class="fas fa-trash"></i></button>
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
        {{ $listas->links() }}          
    </div>   
</div>

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="frm" action="" method="post">            
            @csrf
            @method('DELETE')
            <input id="id_lista" name="lista_id" type="hidden" value=""/>
                <div class="modal-header">
                    <h4 class="modal-title">Remover Lista!</h4>
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
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@stop

@section('footer')
    <strong>Copyright &copy; {{env('DESENVOLVEDOR_INICIO')}} <a href="{{env('DESENVOLVEDOR_URL')}}">{{env('DESENVOLVEDOR')}}</a>.</strong> Desenvolvido por <a href="https://informaticalivre.com.br">Informática Livre</a>.
@endsection

@section('plugins.Toastr', true)

@section('css')
<link href="{{url(asset('backend/plugins/bootstrap-toggle/bootstrap-toggle.min.css'))}}" rel="stylesheet">
@stop

@section('js')
    <script src="{{url(asset('backend/plugins/bootstrap-toggle/bootstrap-toggle.min.js'))}}"></script>
    <script>
       $(function () {           
           
           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
           
            //FUNÇÃO PARA EXCLUIR
            $('.j_modal_btn').click(function() {
                var lista_id = $(this).data('id');                
                $.ajax({
                    type: 'GET',
                    dataType: 'JSON',
                    url: "{{ route('listas.delete') }}",
                    data: {
                       'id': lista_id
                    },
                    success:function(data) {
                        if(data.error){
                            $('.j_param_data').html(data.error);
                            $('#id_lista').val(data.id);
                            $('#frm').prop('action','{{ route('listas.deleteon') }}');
                        }else{
                            $('#frm').prop('action','{{ route('listas.deleteon') }}');
                        }
                    }
                });
            });
            
            $('#toggle-two').bootstrapToggle({
                on: 'Enabled',
                off: 'Disabled'
            });
            
            $('.toggle-class').on('change', function() {
                var status = $(this).prop('checked') == true ? 1 : 0;
                var lista_id = $(this).data('id');
                $.ajax({
                    type: 'GET',
                    dataType: 'JSON',
                    url: "{{ route('listas.listaSetStatus') }}",
                    data: {
                        'status': status,
                        'id': lista_id
                    },
                    success:function(data) {
                        
                    }
                });
            });
        });
    </script>
@endsection