@extends('adminlte::page')

@section('title', 'Editar Projeto')

@php
$config = [
    "height" => "300",
    "fontSizes" => ['8', '9', '10', '11', '12', '14', '18'],
    "lang" => 'pt-BR',
    "toolbar" => [
        // [groupName, [list of button]]
        ['style', ['style']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['style', ['bold', 'italic', 'underline', 'clear']],
        //['font', ['strikethrough', 'superscript', 'subscript']],        
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video','hr']],
        ['view', ['fullscreen', 'codeview']],
    ],
]
@endphp

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1><i class="fas fa-search mr-2"></i>Editar Projeto</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item"><a href="{{route('portifolio.index')}}">Portifólio</a></li>
            <li class="breadcrumb-item active">Editar Projeto</li>
        </ol>
    </div>
</div> 
@stop

@section('content')
<div class="row">
    <div class="col-12">
        @if($errors->all())
            @foreach($errors->all() as $error)
                @message(['color' => 'danger'])
                    {{ $error }}
                @endmessage
            @endforeach
        @endif 

        @if(session()->exists('message'))
            @message(['color' => session()->get('color')])
                {{ session()->get('message') }}
            @endmessage
        @endif
    </div>            
</div>   
                    
            
<form action="{{ route('portifolio.update',['id' => $projeto->id]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
@csrf     
@method('PUT')     
<div class="row">            
<div class="col-12">
<div class="card card-teal card-outline card-outline-tabs">                            
<div class="card-header p-0 border-bottom-0">
<ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Informações</a>
    </li>  
    <li class="nav-item">
        <a class="nav-link" id="custom-tabs-four-settings-tab" data-toggle="pill" href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings" aria-selected="false">Fotos</a>
    </li>      
</ul>
</div>
<div class="card-body">
<div class="tab-content" id="custom-tabs-four-tabContent">
    <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
       
        <div class="row"> 
            <div class="col-12 col-sm-12 col-md-5 col-lg-5"> 
                <div class="form-group">
                    <label class="labelforms text-muted"><b>*Nome do Projeto</b> </label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') ?? $projeto->name }}">
                </div>
            </div> 
            <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                <div class="form-group">
                    <label class="labelforms text-muted"><b>*Empresa:</b> <a style="font-size:11px;" href="{{route('empresas.index')}}">(Cadastrar Empresa)</a></label>
                    <select name="empresa" class="form-control categoria">
                        @if(!empty($empresas) && $empresas->count() > 0)
                            <option value="">Selecione a Empresa</option>
                            @foreach($empresas as $empresa) 
                                <option value="{{ $empresa->id }}" {{ (old('empresa') == $empresa->id ? 'selected' : ($projeto->empresa == $empresa->id ? 'selected' : '')) }}>{{ $empresa->id }} - {{ $empresa->alias_name }}</option>                                                                                                                      
                            @endforeach
                        @else
                            <option value="">Cadastre uma Empresa</option>
                        @endif
                                                                       
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                <div class="form-group">
                    <label class="labelforms text-muted"><b>Status:</b></label>
                    <select name="status" class="form-control">
                        <option value="1" {{ (old('status') == '1' ? 'selected' : ($projeto->status == '1' ? 'selected' : '')) }}>Publicado</option>
                        <option value="0" {{ (old('status') == '0' ? 'selected' : ($projeto->status == '0' ? 'selected' : '')) }}>Rascunho</option>
                    </select>
                </div>
            </div>                                                                
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-5 col-lg-5">   
                <div class="form-group">
                    <label class="labelforms text-muted"><b>Headline</b> <small class="text-info">(Chamada que vai em destaque nas redes sociais)</small></label>
                    <input type="text" class="form-control" name="headline" value="{{old('headline') ?? $projeto->headline}}">
                </div>                                                    
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                <div class="form-group">
                    <label class="labelforms text-muted"><b>*Categoria:</b> <a style="font-size:11px;" href="{{route('catprodutos.index')}}">(Criar categoria)</a></label>
                    <select name="categoria" class="form-control categoria">
                        @if(!empty($catPortifolio) && $catPortifolio->count() > 0)
                            <option value="">Selecione a Categoria</option>
                            @foreach($catPortifolio as $categoria)                                
                                <optgroup label="{{ $categoria->titulo }}">  
                                    @if($categoria->children)
                                        @foreach($categoria->children as $subcategoria)
                                            <option value="{{ $subcategoria->id }}" {{ (old('categoria') == $subcategoria->id ? 'selected' : ($projeto->categoria == $subcategoria->id ? 'selected' : '')) }}>{{ $subcategoria->titulo }}</option>
                                        @endforeach
                                    @endif
                                </optgroup>                                                                                       
                            @endforeach
                        @else
                            <option value="">Cadastre Categorias</option>
                        @endif
                                                                       
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                <div class="form-group">
                    <label class="labelforms text-muted"><b>Exibir:</b></label>
                    <select name="exibir" class="form-control">
                        <option value="1" {{ (old('exibir') == '1' ? 'selected' : ($projeto->exibir == '1' ? 'selected' : '')) }}>Sim</option>
                        <option value="0" {{ (old('exibir') == '0' ? 'selected' : ($projeto->exibir == '0' ? 'selected' : '')) }}>Não</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12 col-sm-12 col-md-5 col-lg-4">
                <div class="form-group">
                    <label class="labelforms"><b>URL do site</b></label>
                    <input type="text" class="form-control text-muted" placeholder="URL do site" name="link" value="{{ old('link') ?? $projeto->link }}">
                </div>
            </div> 
              
            <div class="col-12 col-md-6 col-lg-3"> 
                <div class="form-group">
                    <label class="labelforms text-muted"><b>Data de Início</b></label>
                    <div class="input-group">
                        <input type="text" class="form-control datepicker-here" data-language='pt-BR' name="data_inicio" value="{{ old('data_inicio') ?? $projeto->data_inicio }}"/>
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>                                                    
            </div>
            <div class="col-12 col-md-6 col-lg-3"> 
                <div class="form-group">
                    <label class="labelforms text-muted"><b>Data de Término</b></label>
                    <div class="input-group">
                        <input type="text" class="form-control datepicker-here" data-language='pt-BR' name="data_termino" value="{{ old('data_termino') ?? $projeto->data_termino }}"/>
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>                                                    
            </div> 
            <div class="col-12 col-sm-12 col-md-5 col-lg-2">
                <div class="form-group">
                    <label class="labelforms text-muted"><b>Valor</b></label>
                    <input type="text" class="form-control text-muted mask-money" name="valor" value="{{ old('valor') ?? $projeto->valor }}">
                </div>
            </div>                  
        </div>
       
        <div class="row mb-2">
            <div class="col-12 mb-1"> 
                <div class="form-group">
                    <label class="labelforms text-muted"><b>MetaTags</b></label>
                    <input id="tags_1" class="tags" rows="5" name="tags" value="{{ old('tags') ?? $projeto->tags }}">
                </div>
                <label class="labelforms text-muted"><b>Descrição do Projeto</b></label>
                <x-adminlte-text-editor name="content" v placeholder="Descrição do projeto..." :config="$config">{{ old('content') ?? $projeto->content }}</x-adminlte-text-editor>                                                                                     
            </div>                        
        </div>
                
    </div>                                   
    
    
    <div class="tab-pane fade" id="custom-tabs-four-settings" role="tabpanel" aria-labelledby="custom-tabs-four-settings-tab">
        <div class="row mb-4">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6"> 
                
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6">   
                <div class="form-group">
                    <label class="labelforms text-muted"><b>Legenda da Imagem de Capa</b></label>
                    <input type="text" class="form-control"  name="thumb_legenda" value="{{ old('thumb_legenda') ?? $projeto->thumb_legenda }}">
                </div>                                                    
            </div>
            <div class="col-sm-12">                                        
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="exampleInputFile" name="files[]" multiple>
                        <label class="custom-file-label" for="exampleInputFile">Escolher Fotos</label>
                    </div>
                </div>                                        
                <div class="content_image"></div> 
                                        
                <div class="property_image">
                    @foreach($projeto->images()->get() as $image)
                    <div class="property_image_item">
                        <a href="{{ $image->getUrlImageAttribute() }}" data-toggle="lightbox" data-gallery="property-gallery" data-type="image">
                        <img src="{{ $image->url_cropped }}" alt="">
                        </a>
                        <div class="property_image_actions">
                            <a href="javascript:void(0)" class="btn btn-xs {{ ($image->cover == true ? 'btn-success' : 'btn-default') }} icon-notext image-set-cover px-2" data-action="{{ route('portifolio.imageSetCover', ['image' => $image->id]) }}"><i class="nav-icon fas fa-check"></i> </a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-xs image-remove px-2" data-action="{{ route('portifolio.imageRemove', ['image' => $image->id]) }}"><i class="nav-icon fas fa-times"></i> </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div> 
    </div>   
    

</div>
<div class="row text-right">
    <div class="col-12 my-2">
        <button type="submit" class="btn btn-lg btn-success"><i class="nav-icon fas fa-check mr-2"></i> Atualizar Agora</button>
    </div>
</div> 
                        
</form>                 
            
@stop

@section('css')
    <link href="{{url(asset('backend/plugins/airdatepicker/css/datepicker.min.css'))}}" rel="stylesheet" type="text/css">
    <!--tags input-->
    <link rel="stylesheet" href="{{url(asset('backend/plugins/jquery-tags-input/jquery.tagsinput.css'))}}" />
    <style type="text/css">
        div.tagsinput span.tag {
            background: #65CEA7 !important;
            border-color: #65CEA7;
            color: #fff;
            border-radius: 15px;
            -webkit-border-radius: 15px;
            padding: 3px 10px;
        }
        div.tagsinput span.tag a {
            color: #43886e;    
        }
        /* Lista de ImÃ³veis */
        img {
            max-width: 100%;
        }
        .realty_list_item  {    
            border: 1px solid #F3F3F3;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
        }

        .border-item-imovel{
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            border: 1px solid #F3F3F3;  
            background-color: #F3F3F3;
        }
       
        .property_image, .content_image {
            width: 100%;
            flex-basis: 100%;
            display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
        }
        .property_image .property_image_item, .content_image .property_image_item {
            flex-basis: calc(25% - 20px) !important;
            margin-bottom: 20px;
            margin-right: 20px;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            position: relative;
        }

        .property_image .property_image_item img, .content_image .property_image_item img {
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
        }
        .property_image .property_image_item .property_image_actions, .content_image .property_image_item .property_image_actions {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .embed {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            max-width: 100%;
        }
    </style>
    @stop

@section('js')
<script src="{{url(asset('backend/plugins/airdatepicker/js/datepicker.min.js'))}}"></script>
<script src="{{url(asset('backend/plugins/airdatepicker/js/i18n/datepicker.pt-BR.js'))}}"></script>
<script src="{{url(asset('backend/assets/js/jquery.mask.js'))}}"></script>
<script>
    $(document).ready(function () { 
       var $money = $(".mask-money");
        $money.mask('R$ 000.000.000.000.000,00', {reverse: true, placeholder: "R$ 0,00"});
    });
</script>


<!--tags input-->
<script src="{{url(asset('backend/plugins/jquery-tags-input/jquery.tagsinput.js'))}}"></script>
<script>
    $(function () { 
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }); 
        
        $('input[name="files[]"]').change(function (files) {
            $('.content_image').text('');
            $.each(files.target.files, function (key, value) {
                var reader = new FileReader();
                reader.onload = function (value) {
                    $('.content_image').append(
                        '<div id="list" class="property_image_item">' +
                        '<div class="embed radius" style="background-image: url(' + value.target.result + '); background-size: cover; background-position: center center;"></div>' +
                        '<div class="property_image_actions">' +
                            '<a href="javascript:void(0)" class="btn btn-danger btn-xs image-remove1 px-2"><i class="nav-icon fas fa-times"></i> </a>' +
                        '</div>' +
                        '</div>');
                        
                    $('.image-remove1').click(function(){
                        $(this).closest('#list').remove()
                    });
                };
                reader.readAsDataURL(value);
            });
        }); 

        $('.image-set-cover').click(function (event) {
                event.preventDefault();
                var button = $(this);
                $.post(button.data('action'), {}, function (response) {
                    if (response.success === true) {
                        $('.property_image').find('a.btn-success').removeClass('btn-success');
                        button.addClass('btn-success');
                    }
                    if(response.success === false){
                        button.addClass('btn-default');
                    }
                }, 'json');
            });
            
            $('.image-remove').click(function(event){
                event.preventDefault();
                var button = $(this);
                $.ajax({
                    url: button.data('action'),
                    type: 'DELETE',
                    dataType: 'json',
                    success: function(response){
                        if(response.success === true) {
                            button.closest('.property_image_item').fadeOut(function(){
                                $(this).remove();
                            });
                        }
                    }
                });
            });
            
            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
              event.preventDefault();
              $(this).ekkoLightbox({
                alwaysShowClose: true
              });
            });
        
        //tag input
        function onAddTag(tag) {
            alert("Adicionar uma Tag: " + tag);
        }
        function onRemoveTag(tag) {
            alert("Remover Tag: " + tag);
        }
        function onChangeTag(input,tag) {
            alert("Changed a tag: " + tag);
        }
        $(function() {
            $('#tags_1').tagsInput({
                width:'auto',
                height:200
            });
        });
    });
</script>
@stop