@extends('adminlte::page')

@section('title', 'Editar Produto')

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
            <h1><i class="fas fa-search mr-2"></i>Editar Produto</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
                <li class="breadcrumb-item"><a href="{{route('produtos.index')}}">Produtos</a></li>
                <li class="breadcrumb-item active">Editar Produto</li>
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
                    
            
<form action="{{ route('produtos.update',['id' => $produto->id]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
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
            
                <div class="row mb-2"> 
                    <div class="col-12 col-md-6 col-lg-4"> 
                        <div class="form-group">
                            <label class="labelforms text-muted"><b>*Nome do Produto</b> </label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') ?? $produto->name }}">
                        </div>
                    </div> 
                    <div class="col-12 col-md-6 col-lg-5">   
                        <div class="form-group">
                            <label class="labelforms text-muted"><b>Headline</b> <small class="text-info">(Chamada que vai em destaque nas redes sociais)</small></label>
                            <input type="text" class="form-control" name="headline" value="{{old('headline') ?? $produto->headline}}">
                        </div>                                                    
                    </div> 
                    <div class="col-12 col-sm-4 col-md-4 col-lg-3">
                        <div class="form-group">
                            <label class="labelforms text-muted"><b>Status:</b></label>
                            <select name="status" class="form-control">
                                <option value="1" {{ (old('status') == '1' ? 'selected' : ($produto->status == '1' ? 'selected' : '')) }}>Publicado</option>
                                <option value="0" {{ (old('status') == '0' ? 'selected' : ($produto->status == '0' ? 'selected' : '')) }}>Rascunho</option>
                            </select>
                        </div>
                    </div> 
                    <div class="col-12 col-sm-5 col-md-5 col-lg-4">
                        <div class="form-group">
                            <label class="labelforms text-muted"><b>*Categoria:</b> <a style="font-size:11px;" href="{{route('catprodutos.index')}}">(Criar categoria)</a></label>
                            <select name="categoria" class="form-control categoria">
                                @if(!empty($catProdutos) && $catProdutos->count() > 0)
                                    <option value="">Selecione a Categoria</option>
                                    @foreach($catProdutos as $categoria)                                
                                        <optgroup label="{{ $categoria->titulo }}">  
                                            @if($categoria->children)
                                                @foreach($categoria->children as $subcategoria)
                                                    <option value="{{ $subcategoria->id }}" {{ (old('categoria') == $subcategoria->id ? 'selected' : ($produto->categoria == $subcategoria->id ? 'selected' : '')) }}>{{ $subcategoria->titulo }}</option>
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
                    <div class="col-12 col-sm-3 col-md-3 col-lg-3">
                        <div class="form-group">
                            <label class="labelforms text-muted"><b>Exibir:</b></label>
                            <select name="exibir" class="form-control">
                                <option value="1" {{ (old('exibir') == '1' ? 'selected' : ($produto->exibir == '1' ? 'selected' : '')) }}>Sim</option>
                                <option value="0" {{ (old('exibir') == '0' ? 'selected' : ($produto->exibir == '0' ? 'selected' : '')) }}>Não</option>
                            </select>
                        </div>
                    </div>                          
                </div>
                <div class="row mb-2">
                    
                    
                </div>
                <div class="row mb-2">
                    <div class="col-12"> 
                        <div class="form-group">
                            <label class="labelforms text-muted"><b>Tipo de pagamento</b></label>
                            <div class="form-check">
                                <input id="tipounico" class="form-check-input" type="radio" value="1" name="tipo_pagamento" {{(old('tipo_pagamento') == '1' ? 'checked' : ($produto->tipo_pagamento == '1' ? 'checked' : ''))}}>
                                <label for="tipounico" class="form-check-label mr-5">Único</label>
                                <input id="tiporecorrente" class="form-check-input" type="radio" value="0" name="tipo_pagamento" {{(old('tipo_pagamento') == '0' ? 'checked' : ($produto->tipo_pagamento == '0' ? 'checked' : '') )}}>
                                <label for="tiporecorrente" class="form-check-label">Recorrente</label>
                            </div>
                        </div>
                    </div>                        
                </div>

                <div class="row mb-2">
                    <div class="col-12 col-sm-4 col-md-4 col-lg-2">
                        <label class="labelforms text-muted"><b>Valor</b></label>
                        <input type="text" class="form-control mask-money v" name="valor" value="{{ old('valor') ?? $produto->valor }}">
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-2">
                        <label class="labelforms text-muted"><b>Mensal</b></label>
                        <input type="text" class="form-control mask-money m" name="valor_mensal" value="{{ old('valor_mensal') ?? $produto->valor_mensal }}">
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-2">
                        <label class="labelforms text-muted"><b>Trimestral</b></label>
                        <input type="text" class="form-control mask-money t" name="valor_trimestral" value="{{ old('valor_trimestral') ?? $produto->valor_trimestral }}">
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-2">
                        <label class="labelforms text-muted"><b>Semestral</b></label>
                        <input type="text" class="form-control mask-money s" name="valor_semestral" value="{{ old('valor_semestral') ?? $produto->valor_semestral }}">
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-2">
                        <label class="labelforms text-muted"><b>Anual</b></label>
                        <input type="text" class="form-control mask-money a" name="valor_anual" value="{{ old('valor_anual') ?? $produto->valor_anual }}">
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-2">
                        <label class="labelforms text-muted"><b>Bi-anual</b></label>
                        <input type="text" class="form-control mask-money b" name="valor_bianual" value="{{ old('valor_bianual') ?? $produto->valor_bianual }}">
                    </div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-12 mb-1"> 
                        <div class="form-group">
                            <label class="labelforms text-muted"><b>MetaTags</b></label>
                            <input id="tags_1" class="tags" rows="5" name="tags" value="{{ old('tags') ?? $produto->tags }}">
                        </div>
                    </div>
                    <div class="col-12">   
                        <label class="labelforms text-muted"><b>Descrição do Produto</b></label>
                        <x-adminlte-text-editor name="content" v placeholder="Descrição do produto..." :config="$config">{{ old('content') ?? $produto->content }}</x-adminlte-text-editor>                                                                                     
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
                            <input type="text" class="form-control"  name="thumb_legenda" value="{{ old('thumb_legenda') ?? $produto->thumb_legenda }}">
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
                            @foreach($produto->images()->get() as $image)
                            <div class="property_image_item">
                                <a href="{{ $image->getUrlImageAttribute() }}" data-toggle="lightbox" data-gallery="property-gallery" data-type="image">
                                <img src="{{ $image->url_cropped }}" alt="">
                                </a>
                                <div class="property_image_actions">
                                    <a href="javascript:void(0)" class="btn btn-xs {{ ($image->cover == true ? 'btn-success' : 'btn-default') }} icon-notext image-set-cover px-2" data-action="{{ route('produtos.imageSetCover', ['image' => $image->id]) }}"><i class="nav-icon fas fa-check"></i> </a>
                                    <a href="javascript:void(0)" class="btn btn-danger btn-xs image-remove px-2" data-action="{{ route('produtos.imageRemove', ['image' => $image->id]) }}"><i class="nav-icon fas fa-times"></i> </a>
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
    </div> 
</div>
</div>
</div>                      
</form>                 
            
@stop

@section('css')
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

        function tipoPagamento() {
            if ($('#tipounico').prop('checked')) {
                $('.m, .t, .s, .a, .b').prop('disabled', true);
                $('.v').prop('disabled', false);
            }else if($('#tiporecorrente').prop('checked')){
                $('.m, .t, .s, .a, .b').prop('disabled', false);
                $('.v').prop('disabled', true);
            }else{
                $('.v, .m, .t, .s, .a, .b').prop('disabled', true);
            }
        } 

        tipoPagamento();

        $('#tipounico').change( function(){
            if(this.checked){
                $('.m, .t, .s, .a, .b').prop('disabled', true);
                $('.v').prop('disabled', false);
            }
        });
        $('#tiporecorrente').change( function(){
            if(this.checked){
                $('.v').prop('disabled', true);
                $('.m, .t, .s, .a, .b').prop('disabled', false);
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
                            '<a href="javascript:void(0)" class="btn btn-danger btn-xs image-remove px-2"><i class="nav-icon fas fa-times"></i> </a>' +
                        '</div>' +
                        '</div>');

                    $('.image-remove').click(function(){
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