@extends('adminlte::page')

@section('title', 'Gerenciar Serviços')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1><i class="fas fa-search mr-2"></i> Serviços</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">                    
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item active">Serviços</li>
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
                        <form class="input-group input-group-sm" action="{{route('servicos.search')}}" method="post">
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
                <a href="{{route('servicos.create')}}" class="btn btn-sm btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Novo</a>
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
        @if(!empty($servicos) && $servicos->count() > 0)
            <table class="table table-bordered table-striped projects">
                <thead>
                    <tr>
                        <th class="text-center">Capa</th>
                        <th>servico</th>
                        <th class="text-center">Imagens</th>
                        <th class="text-center">Views</th>
                        <th class="text-center">Valor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servicos as $servico)                    
                    <tr style="{{ ($servico->status == '1' ? '' : 'background: #fffed8 !important;')  }}">
                        <td class="text-center">
                            <a href="{{url($servico->nocover())}}" data-title="{{$servico->name}}" data-toggle="lightbox">
                                <img alt="{{$servico->name}}" src="{{url($servico->cover())}}" width="60">
                            </a>
                        </td>
                        <td>{{$servico->name}}</td>
                        <td class="text-center">{{$servico->countimages()}}</td>
                        <td class="text-center">{{$servico->views}}</td>
                        <td class="text-center">
                            @if ($servico->tipo_pagamento == 1)
                                R${{$servico->valor}}
                            @else
                                {{($servico->valor_mensal ? 'Mensal: R$'.$servico->valor_mensal : '')}}
                                {!!($servico->valor_trimestral ? '<br>Trimestral: R$'.$servico->valor_trimestral : '')!!}
                                {!!($servico->valor_semestral ? '<br>Semestral: R$'.$servico->valor_semestral : '')!!}
                                {!!($servico->valor_anual ? '<br>Anual: R$'.$servico->valor_anual : '')!!}
                                {!!($servico->valor_bianual ? '<br>Bianual: R$'.$servico->valor_bianual : '')!!}
                            @endif
                        </td>
                        <td>
                            <input type="checkbox" data-onstyle="success" data-offstyle="warning" data-size="mini" class="toggle-class" data-id="{{ $servico->id }}" data-toggle="toggle" data-style="slow" data-on="<i class='fas fa-check'></i>" data-off="<i style='color:#fff !important;' class='fas fa-exclamation-triangle'></i>" {{ $servico->status == true ? 'checked' : ''}}>
                            <a href="{{route('servicos.edit',['id' => $servico->id])}}" class="btn btn-xs btn-default"><i class="fas fa-pen"></i></a>
                            <button type="button" class="btn btn-xs btn-danger text-white j_modal_btn" data-id="{{$servico->id}}" data-toggle="modal" data-target="#modal-default">
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
            {{ $servicos->appends($filters)->links() }}
        @else
            {{ $servicos->links() }}
        @endif          
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
        <input id="id_servico" name="servico_id" type="hidden" value=""/>
            <div class="modal-header">
                <h4 class="modal-title">Remover servico!</h4>
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
            
            //FUNÇÃO PARA EXCLUIR
            $('.j_modal_btn').click(function() {
                var servico_id = $(this).data('id');                
                $.ajax({
                    type: 'GET',
                    dataType: 'JSON',
                    url: "{{ route('servicos.delete') }}",
                    data: {
                       'id': servico_id
                    },
                    success:function(data) {
                        if(data.error){
                            $('.j_param_data').html(data.error);
                            $('#id_servico').val(data.id);
                            $('#frm').prop('action','{{ route('servicos.deleteon') }}');
                        }else{
                            $('#frm').prop('action','{{ route('servicos.deleteon') }}');
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
                var servico_id = $(this).data('id');
                $.ajax({
                    type: 'GET',
                    dataType: 'JSON',
                    url: "{{ route('servicos.servicoSetStatus') }}",
                    data: {
                        'status': status,
                        'id': servico_id
                    },
                    success:function(data) {
                        
                    }
                });
            });
        });
    </script>
@endsection