@extends('adminlte::page')

@section('title', 'Gerenciar Cofre')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1><i class="fas fa-search mr-2"></i> Cofre</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">                    
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item active">Cofre</li>
        </ol>
    </div>
</div>
@stop

@section('content')

    <div class="card">
        <div class="card-header text-right">
            <a href="{{route('cofre.create')}}" class="btn btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Item</a>
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
            @if(!empty($items) && $items->count() > 0)
                <table id="example1" class="table table-bordered table-striped projects">
                    <thead>
                        <tr>
                            <th>Logomarca</th>
                            <th>Nome</th>
                            <th>Login</th>
                            <th>Password</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody> 
                        @foreach($items as $item)
                        <tr>
                            <td class="text-center">
                                <a href="{{$item->cover()}}" data-title="{{$item->name}}" data-toggle="lightbox">
                                    <img style="width: 50px;" alt="{{$item->name}}" src="{{$item->cover()}}">
                                </a>
                            </td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->login}}</td>
                            <td><span id="magic" data-password="{{$item->password}}">**************</span></td>
                            <td>
                                <input type="checkbox" data-onstyle="success" data-offstyle="warning" data-size="mini" class="toggle-class" data-id="{{ $item->id }}" data-toggle="toggle" data-style="slow" data-on="<i class='fas fa-check'></i>" data-off="<i style='color:#fff !important;' class='fas fa-exclamation-triangle'></i>" {{ $item->status == true ? 'checked' : ''}}>
                                <a href="{{route('cofre.edit',['id' => $item->id])}}" class="btn btn-xs btn-default"><i class="fas fa-pen"></i></a>
                                <button type="button" class="btn btn-xs btn-danger text-white j_modal_btn" data-id="{{$item->id}}" data-toggle="modal" data-target="#modal-default">
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
            {{ $items->links() }}
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->   
  

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="frm" action="" method="post">            
            @csrf
            @method('DELETE')
            <input id="id_item" name="item_id" type="hidden" value=""/>
                <div class="modal-header">
                    <h4 class="modal-title">Remover Item!</h4>
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

@endsection

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
            var item_id = $(this).data('id');
            $.ajax({
                type: 'GET',
                dataType: 'JSON',
                url: "{{ route('cofre.delete') }}",
                data: {
                   'id': item_id
                },
                success:function(data) {
                    console.log(data);
                    if(data.error){
                        $('.j_param_data').html(data.error);
                        $('#id_item').val(data.id);
                        $('#frm').prop('action', '{{ route('cofre.deleteon') }}');
                    }else{
                        $('#frm').prop('action', '{{ route('cofre.deleteon') }}');
                    }
                }
            });
        });

        $('.toggle-class').on('change', function() {
            var status = $(this).prop('checked') == true ? 1 : 0;
            var item_id = $(this).data('id');
            $.ajax({
                type: 'GET',
                dataType: 'JSON',
                url: "{{ route('cofre.itemSetStatus') }}",
                data: {
                    'status': status,
                    'id': item_id
                },
                success:function(data) {
                    
                }
            });
        });
        
        var $magics = $('#magic');
        $magics.one('click', function () {
            var magic = $(this);
            var password = magic.data('password');
            magic.text(password);
        });

    });
</script>
@endsection