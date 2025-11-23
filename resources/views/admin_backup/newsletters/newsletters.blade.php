@extends('adminlte::page')

@section('title', 'Gerenciar Emails')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1><i class="fas fa-suitcase mr-2"></i> {{$lista->titulo}}</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">                    
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item"><a href="{{route('listas')}}">Listas</a></li>
            <li class="breadcrumb-item active">Emails</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-header text-right">
        <a href="{{route('lista.newsletter.create')}}" class="btn btn-sm btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Email</a>
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
        @if(!empty($emails) && $emails->count() > 0)
            <table class="table table-bordered table-striped projects">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Cadastro</th>
                        <th class="text-center">Autorizado</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emails as $email)                    
                    <tr style="{{ ($email->status == '1' ? '' : 'background: #fffed8 !important;')  }}">                            
                        <td>{{ $email->nome }}</td>
                        <td class="text-center">{{ $email->email }}</td>
                        <td class="text-center">{{ $email->created_at }}</td>                           
                        <td class="text-center">{!! $email->autorizacao !!}</td>                           
                        <td class="acoes">
                            <input type="checkbox" data-onstyle="success" data-offstyle="warning" data-size="mini" class="toggle-class" data-id="{{ $email->id }}" data-toggle="toggle" data-style="slow" data-on="<i class='fas fa-check'></i>" data-off="<i style='color:#fff !important;' class='fas fa-exclamation-triangle'></i>" {{ $email->status == true ? 'checked' : ''}}>
                            <form class="btn btn-xs" action="{{route('email.send')}}" method="post">
                                @csrf
                                <input type="hidden" name="nome" value="{{ $email->nome }}">
                                <input type="hidden" name="email" value="{{ $email->email }}">
                                <button title="Enviar Email" type="submit" class="btn btn-xs text-white bg-teal"><i class="fas fa-envelope"></i></button>
                            </form>
                            <a data-toggle="tooltip" data-placement="top" title="Editar Email" href="{{route('listas.newsletter.edit',['id' => $email->id])}}" class="btn btn-xs btn-default"><i class="fas fa-pen"></i></a>
                            <button data-placement="top" title="Remover Email" type="button" class="btn btn-xs btn-danger text-white j_modal_btn" data-id="{{$email->id}}" data-toggle="modal" data-target="#modal-default"><i class="fas fa-trash"></i></button>
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
        {{ $emails->links() }}          
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
    </div>
</div>
@stop

@section('footer')
    <strong>Copyright &copy; {{env('DESENVOLVEDOR_INICIO')}} <a href="{{env('DESENVOLVEDOR_URL')}}">{{env('DESENVOLVEDOR')}}</a>.</strong> Desenvolvido por <a href="https://informaticalivre.com.br">Informática Livre</a>.
@endsection

{{-- @section('plugins.Toastr', true) --}}

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