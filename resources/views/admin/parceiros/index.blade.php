@extends('adminlte::page')

@section('title', 'Gerenciar Parceiros')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1><i class="fas fa-search mr-2"></i> Parceiros</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">                    
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item active">Parceiros</li>
        </ol>
    </div>
</div>
@stop

@section('content')

<section class="content">    
      <div class="card card-solid">
        <div class="card-header text-right">
            <a href="{{route('parceiros.create')}}" class="btn btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Parceiro</a>
        </div>
        <div class="card-body pb-0">
            <div class="row">
                <div class="col-12">                
                    @if(session()->exists('message'))
                        @message(['color' => session()->get('color')])
                            {{ session()->get('message') }}
                        @endmessage
                    @endif
                </div>            
            </div>
            @if(!empty($parceiros) && $parceiros->count() > 0)
                <div class="row d-flex align-items-stretch">
                @foreach($parceiros as $parceiro)  
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 d-flex align-items-stretch">
                  <div class="card bg-light" style="{{ ($parceiro->status == '1' ? '' : 'background: #fffed8 !important;')  }}">
                    <div class="card-header text-muted border-bottom-0">
                        <b>{{$parceiro->name}}</b>
                    </div>
                    <div class="card-body pt-0">
                      <div class="row">
                        <div class="col-7">
                          <p class="my-3 text-muted"><b>Data de Criação: </b>{{$parceiro->created_at}}</p>
                          <ul class="ml-4 mb-0 fa-ul text-muted">
                            <li class="small my-2">
                                @if($parceiro->rua != '')
                                <span class="fa-li">
                                    <i class="fas fa-lg fa-home"></i>
                                </span> {{$parceiro->rua}}
                                @endif
                                @if($parceiro->rua != '' && $parceiro->num != '')
                                    , {{$parceiro->num}}
                                @endif
                                @if($parceiro->rua != '' || $parceiro->num != '')
                                    , {{$parceiro->bairro}}
                                @endif
                                @if($parceiro->rua != '' || $parceiro->num != '' || $parceiro->bairro != '')
                                    - {{ getCidadeNome($parceiro->cidade, 'cidades') }}
                                @endif
                            </li>
                            @if($parceiro->telefone)
                                <li class="small my-2">
                                    <span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> 
                                    {{$parceiro->telefone}}
                                </li>
                            @endif
                            @if($parceiro->celular)
                                <li class="small my-2">
                                    <span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> 
                                    {{$parceiro->celular}}                                   
                                </li>
                            @endif
                          </ul> 
                          <p class="my-3">
                            @if (!empty($parceiro->facebook))
                                <a class="mr-2" target="_blank" href="{{$parceiro->facebook}}"><i class="fab fa-facebook"></i></a>
                            @endif                                
                            @if (!empty($parceiro->twitter))
                                <a class="mr-2" target="_blank" href="{{$parceiro->twitter}}"><i class="fab fa-twitter"></i></a>
                            @endif                                
                            @if (!empty($parceiro->instagram))
                                <a class="mr-2" target="_blank" href="{{$parceiro->instagram}}"><i class="fab fa-instagram"></i></a>
                            @endif                                
                            @if (!empty($parceiro->youtube))
                                <a class="mr-2" target="_blank" href="{{$parceiro->youtube}}"><i class="fab fa-youtube"></i></a>
                            @endif                                
                            @if (!empty($parceiro->linkedin))
                                <a class="mr-2" target="_blank" href="{{$parceiro->linkedin}}"><i class="fab fa-linkedin"></i></a>
                            @endif                                
                            @if (!empty($parceiro->vimeo))
                                <a class="mr-2" target="_blank" href="{{$parceiro->vimeo}}"><i class="fab fa-vimeo"></i></a>
                            @endif                                
                            @if (!empty($parceiro->fliccr))
                                <a class="mr-2" target="_blank" href="{{$parceiro->fliccr}}"><i class="fab fa-flickr"></i></a>
                            @endif                                
                            @if (!empty($parceiro->soundclound))
                                <a class="mr-2" target="_blank" href="{{$parceiro->soundclound}}"><i class="fab fa-soundcloud"></i></a>
                            @endif                                
                            @if (!empty($parceiro->snapchat))
                                <a class="mr-2" target="_blank" href="{{$parceiro->snapchat}}"><i class="fab fa-snapchat"></i></a>
                            @endif 
                           </p>                         
                        </div>
                        <div class="col-5 text-center">                        
                          <img src="{{$parceiro->cover()}}" alt="{{$parceiro->cover()}}" class="img-circle img-fluid">
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">                        
                      <div class="text-right"> 
                        <span style="float: left;" class="text-muted"><i class="fas fa-lg fa-eye"></i> {{$parceiro->views}}</span>  
                        <input type="checkbox" data-onstyle="success" data-offstyle="warning" data-size="mini" class="toggle-class" data-id="{{ $parceiro->id }}" data-toggle="toggle" data-style="slow" data-on="<i class='fas fa-check'></i>" data-off="<i style='color:#fff !important;' class='fas fa-exclamation-triangle'></i>" {{ $parceiro->status == true ? 'checked' : ''}}> 
                        
                        @if($parceiro->whatsapp != '')
                            <a target="_blank" href="{{getNumZap($parceiro->whatsapp)}}" class="btn btn-xs btn-success text-white"><i class="fab fa-whatsapp"></i></a>
                        @endif 
                        <a href="{{route('email.send',['id' => $parceiro->id, 'parametro' => 'parceiro'] )}}" class="btn btn-xs text-white bg-teal"><i class="fas fa-envelope"></i></a>
                        @if (!empty($parceiro->link))
                        <a target="_blank" href="{{$parceiro->link}}" class="btn btn-xs btn-default"><i class="fas fa-link"></i></a>
                        @endif                   
                        <a target="_blank" href="{{route('web.parceiro',['slug' => $parceiro->slug])}}" class="btn btn-xs btn-primary"><i class="fas fa-search"></i></a>
                        
                        <a href="{{route('parceiros.edit',['id' => $parceiro->id])}}" class="btn btn-xs btn-default"><i class="fas fa-pen"></i></a>
                        
                        
                        <button type="button" class="btn btn-xs btn-danger text-white j_modal_btn" data-campo="{{$parceiro->name}}" data-id="{{$parceiro->id}}" data-toggle="modal" data-target="#modal-default">
                            <i class="fas fa-trash"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach            
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
        <!-- /.card-body -->
        <div class="card-footer paginacao">  
            {{ $parceiros->links() }}
        </div>
          
      </div>
      <!-- /.card -->
</section>


<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="frm" action="" method="post">            
            @csrf
            @method('DELETE')
            <input id="id_parceiro" name="parceiro_id" type="hidden" value=""/>
                <div class="modal-header">
                    <h4 class="modal-title">Remover Parceiro!</h4>
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
                var parceiro_id = $(this).data('id');                
                $.ajax({
                    type: 'GET',
                    dataType: 'JSON',
                    url: "{{ route('parceiros.delete') }}",
                    data: {
                       'id': parceiro_id
                    },
                    success:function(data) {
                        if(data.error){
                            $('.j_param_data').html(data.error);
                            $('#id_parceiro').val(data.id);
                            $('#frm').prop('action','{{ route('parceiros.deleteon') }}');
                        }else{
                            $('#frm').prop('action','{{ route('parceiros.deleteon') }}');
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
                var parceiro_id = $(this).data('id');
                $.ajax({
                    type: 'GET',
                    dataType: 'JSON',
                    url: '{{ route('parceiros.parceiroSetStatus') }}',
                    data: {
                        'status': status,
                        'id': parceiro_id
                    },
                    success:function(data) {
                        
                    }
                });
            });
        });
    </script>
@endsection