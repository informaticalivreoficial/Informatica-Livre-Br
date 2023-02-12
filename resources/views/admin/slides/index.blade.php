@extends('adminlte::page')

@section('title', 'Gerenciar Slides')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1><i class="fas fa-search mr-2"></i> Slides</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">                    
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item active">Slides</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-header text-right">
        <a href="{{route('slides.create')}}" class="btn btn-sm btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Novo</a>
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
        @if(!empty($slides) && $slides->count() > 0)
            <table class="table table-bordered table-striped projects">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>Título</th>
                        <th>Criado em:</th>
                        <th>Expira:</th>
                        <th>Link</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($slides as $slide)                    
                    <tr style="{{ ($slide->status == '1' ? '' : 'background: #fffed8 !important;')  }}">                            
                        <td class="text-center">
                            @php
                                if(!empty($slide->imagem) && \Illuminate\Support\Facades\Storage::exists($slide->imagem)){
                                    $cover = \Illuminate\Support\Facades\Storage::url($slide->imagem);
                                } else {
                                    $cover = url(asset('backend/assets/images/image.jpg'));
                                }
                            @endphp
                            <a href="{{url($cover)}}" data-title="{{$slide->titulo}}" data-toggle="lightbox">
                                <img alt="{{$slide->titulo}}" src="{{url($cover)}}" width="80">
                            </a>
                        </td>
                        <td>{{$slide->titulo}}</td>
                        <td class="text-center">{{$slide->created_at}}</td>
                        <td class="text-center">                            
                            @if ($slide->getDataExpira() == true)
                                <span class="bg-red p-1">{{\Carbon\Carbon::parse($slide->expira)->format('d/m/Y')}}</span>
                            @else
                                <span class="bg-green p-1">{{\Carbon\Carbon::parse($slide->expira)->format('d/m/Y')}}</span> 
                            @endif
                        </td>
                        <td class="text-center"><a target="_blank" href="{{($slide->link != '' ? $slide->link : '#')}}"><i class="fas fa-link"></i></a></td>                            
                        <td class="acoes">
                            <input type="checkbox" data-onstyle="success" data-offstyle="warning" data-size="mini" class="toggle-class" data-id="{{ $slide->id }}" data-toggle="toggle" data-style="slow" data-on="<i class='fas fa-check'></i>" data-off="<i style='color:#fff !important;' class='fas fa-exclamation-triangle'></i>" {{ $slide->status == true ? 'checked' : ''}}>
                            <a data-toggle="tooltip" data-placement="top" title="Editar Slide" href="{{route('slides.edit',$slide->id)}}" class="btn btn-xs btn-default"><i class="fas fa-pen"></i></a>
                            <button data-placement="top" title="Remover Slide" type="button" class="btn btn-xs btn-danger text-white j_modal_btn" data-id="{{$slide->id}}" data-toggle="modal" data-target="#modal-default"><i class="fas fa-trash"></i></button>
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
        {{ $slides->links() }}          
    </div>   
</div>

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="frm" action="" method="post">            
            @csrf
            @method('DELETE')
            <input id="id_slide" name="slide_id" type="hidden" value=""/>
                <div class="modal-header">
                    <h4 class="modal-title">Remover Slide!</h4>
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
                var slide_id = $(this).data('id');                
                $.ajax({
                    type: 'GET',
                    dataType: 'JSON',
                    url: "{{ route('slides.delete') }}",
                    data: {
                       'id': slide_id
                    },
                    success:function(data) {
                        if(data.error){
                            $('.j_param_data').html(data.error);
                            $('#id_slide').val(data.id);
                            $('#frm').prop('action','{{ route('slides.deleteon') }}');
                        }else{
                            $('#frm').prop('action','{{ route('slides.deleteon') }}');
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
                var slide_id = $(this).data('id');
                $.ajax({
                    type: 'GET',
                    dataType: 'JSON',
                    url: "{{ route('slides.slideSetStatus') }}",
                    data: {
                        'status': status,
                        'id': slide_id
                    },
                    success:function(data) {
                        
                    }
                });
            });
        });
    </script>
@endsection