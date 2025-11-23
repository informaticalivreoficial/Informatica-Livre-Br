@extends('adminlte::page')

@section('title', 'Editar Parceiro')

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
        <h1><i class="fas fa-search mr-2"></i>Editar Parceiro</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item"><a href="{{route('parceiros.index')}}">Parceiros</a></li>
            <li class="breadcrumb-item active">Editar Parceiro</li>
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
                    
            
<form action="{{ route('parceiros.update',['id' => $parceiro->id]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
@csrf
@method('PUT')   
<input type="hidden" name="id" value="{{ $parceiro->id }}">      
<div class="row">            
<div class="col-12">
<div class="card card-teal card-outline card-outline-tabs">                            
<div class="card-header p-0 border-bottom-0">
<ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">INFORMAÇÕES</a>
    </li>  
    <li class="nav-item">
        <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">REDES SOCIAIS</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="custom-tabs-four-settings-tab" data-toggle="pill" href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings" aria-selected="false">IMAGENS</a>
    </li>     
    <li class="nav-item">
        <a class="nav-link" id="custom-tabs-four-seo-tab" data-toggle="pill" href="#custom-tabs-four-seo" role="tab" aria-controls="custom-tabs-four-mapas" aria-selected="false">SEO</a>
    </li> 
</ul>
</div>
<div class="card-body">
<div class="tab-content" id="custom-tabs-four-tabContent">
    <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
        <div class="row mb-4">
            <div class="col-12 col-md-6 col-lg-4"> 
                <div class="form-group">
                    <div class="thumb_user_admin">
                        <img id="preview" src="{{$parceiro->cover()}}" alt="{{ old('name') }}" title="{{ old('name') }}"/>
                        <input id="img-input" type="file" name="logomarca">
                    </div>                                                
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-8">
                <div class="row mb-2">
                    <div class="col-12 col-md-8 col-lg-8"> 
                        <div class="form-group">
                            <label class="labelforms text-muted"><b>*Nome do Parceiro</b> </label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') ?? $parceiro->name }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4"> 
                        <div class="form-group">
                            <label class="labelforms text-muted"><b>Status:</b></label>
                            <select name="status" class="form-control">
                                <option value="1" {{ (old('status') == '1' ? 'selected' : ($parceiro->status == '1' ? 'selected' : '')) }}>Ativo</option>
                                <option value="0" {{ (old('status') == '0' ? 'selected' : ($parceiro->status == '0' ? 'selected' : '')) }}>Inativo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12"> 
                        <div class="form-group">
                            <label class="labelforms text-muted"><b>Url</b> </label>
                            <input type="text" class="form-control" name="link" value="{{ old('link') ?? $parceiro->link }}">
                        </div>
                    </div>                    
                </div>
            </div>
            
        </div>
        

        <div id="accordion">
            <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->
            <div class="card">
                <div class="card-header">
                    <h4>
                        <a style="border:none;color: #555;" data-toggle="collapse" data-parent="#accordion" href="#collapseEndereco">
                            <i class="nav-icon fas fa-plus mr-2"></i> Endereço
                        </a>
                    </h4>
                </div>
                <div id="collapseEndereco" class="panel-collapse collapse show">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-12 col-md-4 col-lg-4"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>*Estado:</b></label>
                                    <select id="state-dd" class="form-control" name="uf">
                                        @if(!empty($estados))
                                            <option value="">Selecione o Estado</option>
                                            @foreach($estados as $estado)
                                                <option value="{{$estado->estado_id}}" {{ (old('uf') == $estado->estado_id ? 'selected' : ($parceiro->uf == $estado->estado_id ? 'selected' : '')) }}>{{$estado->estado_nome}}</option>
                                            @endforeach                                                                        
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-4"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>*Cidade:</b></label>
                                    <select id="city-dd" class="form-control" name="cidade">
                                        @if(!empty($cidades)))
                                            <option value="">Selecione o Estado</option>
                                            @foreach($cidades as $cidade)
                                                <option value="{{$cidade->cidade_id}}" {{ (old('cidade') == $cidade->cidade_id ? 'selected' : ($cidade->cidade_id == $parceiro->cidade ? 'selected' : '')) }}>{{$cidade->cidade_nome}}</option>                                                                   
                                            @endforeach                                                                        
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-4"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Bairro:</b></label>
                                    <input type="text" class="form-control" placeholder="Bairro" name="bairro" value="{{old('bairro') ?? $parceiro->bairro}}">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12 col-md-6 col-lg-5"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Endereço:</b></label>
                                    <input type="text" class="form-control" placeholder="Endereço Completo" name="rua" value="{{old('rua') ?? $parceiro->rua}}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-2"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Número:</b></label>
                                    <input type="text" class="form-control" placeholder="Número do Endereço" name="num" value="{{old('num') ?? $parceiro->num}}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Complemento:</b></label>
                                    <input type="text" class="form-control" placeholder="Complemento (Opcional)" name="complemento" value="{{old('complemento') ?? $parceiro->complemento}}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-2"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>CEP:</b></label>
                                    <input type="text" class="form-control mask-zipcode" placeholder="Digite o CEP" name="cep" value="{{old('cep') ?? $parceiro->cep}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>
                        <a style="border:none;color: #555;" data-toggle="collapse" data-parent="#accordion" href="#collapseContato">
                            <i class="nav-icon fas fa-plus mr-2"></i> Contato
                        </a>
                    </h4>
                </div>
                <div id="collapseContato" class="panel-collapse collapse show">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-12 col-md-6 col-lg-4"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>*Email:</b></label>
                                    <input type="text" class="form-control" placeholder="Email" name="email" value="{{old('email') ?? $parceiro->email}}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Telefone Fixo:</b></label>
                                    <input type="text" class="form-control telefonemask" placeholder="Número do Telefone com DDD" name="telefone" value="{{old('telefone') ?? $parceiro->telefone}}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>*Celular:</b></label>
                                    <input type="text" class="form-control celularmask" placeholder="Número do Celular com DDD" name="celular" value="{{old('celular') ?? $parceiro->celular}}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-2"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>WhatsApp:</b></label>
                                    <input type="text" class="form-control whatsappmask" placeholder="Número do Celular com DDD" name="whatsapp" value="{{old('whatsapp') ?? $parceiro->whatsapp}}">
                                </div>
                            </div>                                                    
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div class="row mb-2">
            <div class="col-12">   
                <label class="labelforms text-muted"><b>Descrição</b></label>
                <x-adminlte-text-editor name="content" v placeholder="Descrição..." :config="$config">{{ old('content') ?? $parceiro->content }}</x-adminlte-text-editor>                                                                                     
            </div>
            
        </div>
                
    </div>
    
    <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">                                    
        <div class="row mb-2 text-muted">
            <div class="col-sm-12 text-muted">
                <div class="form-group">
                    <h5><b>Redes Sociais</b></h5>                                    
                </div>
            </div>
            <hr>
            <div class="col-12 col-md-6 col-lg-6"> 
                <div class="form-group">
                    <label class="labelforms"><b>Facebook:</b></label>
                    <input type="text" class="form-control text-muted" placeholder="Facebook" name="facebook" value="{{old('facebook') ?? $parceiro->facebook}}">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6"> 
                <div class="form-group">
                    <label class="labelforms"><b>Twitter:</b></label>
                    <input type="text" class="form-control text-muted" placeholder="Twitter" name="twitter" value="{{old('twitter') ?? $parceiro->twitter}}">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6"> 
                <div class="form-group">
                    <label class="labelforms"><b>Youtube:</b></label>
                    <input type="text" class="form-control text-muted" placeholder="Youtube" name="youtube" value="{{old('youtube') ?? $parceiro->youtube}}">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6"> 
                <div class="form-group">
                    <label class="labelforms"><b>Flickr:</b></label>
                    <input type="text" class="form-control text-muted" placeholder="Flickr" name="fliccr" value="{{old('fliccr') ?? $parceiro->fliccr}}">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6"> 
                <div class="form-group">
                    <label class="labelforms"><b>Instagram:</b></label>
                    <input type="text" class="form-control text-muted" placeholder="Instagram" name="instagram" value="{{old('instagram') ?? $parceiro->instagram}}">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6"> 
                <div class="form-group">
                    <label class="labelforms"><b>Vimeo:</b></label>
                    <input type="text" class="form-control text-muted" placeholder="Vimeo" name="vimeo" value="{{old('vimeo') ?? $parceiro->vimeo}}">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6"> 
                <div class="form-group">
                    <label class="labelforms"><b>Linkedin:</b></label>
                    <input type="text" class="form-control text-muted" placeholder="Linkedin" name="linkedin" value="{{old('linkedin') ?? $parceiro->linkedin}}">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6"> 
                <div class="form-group">
                    <label class="labelforms"><b>Sound Cloud:</b></label>
                    <input type="text" class="form-control text-muted" placeholder="Linkedin" name="soundclound" value="{{old('soundclound') ?? $parceiro->soundclound}}">
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6"> 
                <div class="form-group">
                    <label class="labelforms"><b>SnapChat:</b></label>
                    <input type="text" class="form-control text-muted" placeholder="SnapChat" name="snapchat" value="{{old('snapchat') ?? $parceiro->snapchat}}">
                </div>
            </div>
        </div>
    </div>
    
    <div class="tab-pane fade" id="custom-tabs-four-settings" role="tabpanel" aria-labelledby="custom-tabs-four-settings-tab">
        <div class="row mb-4">           
            <div class="col-sm-12">                                        
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="exampleInputFile" name="files[]" multiple>
                        <label class="custom-file-label" for="exampleInputFile">Escolher Fotos</label>
                    </div>
                </div>     

                <div class="content_image"></div>

                <div class="property_image">
                    @foreach($parceiro->images()->get() as $image)
                    <div class="property_image_item">
                        <a href="{{ $image->url_image }}" data-toggle="lightbox" data-title="{{$parceiro->name}}" data-gallery="property-gallery" data-type="image">
                            <img src="{{ $image->url_cropped }}" alt="{{$parceiro->name}}">
                        </a>
                        <div class="property_image_actions">
                            <a href="javascript:void(0)" class="btn btn-xs {{ ($image->cover == true ? 'btn-success' : 'btn-default') }} icon-notext image-set-cover px-2" data-action="{{ route('parceiros.imageSetCover', ['image' => $image->id]) }}"><i class="nav-icon fas fa-check"></i> </a>
                            <a href="javascript:void(0)" class="btn btn-danger btn-xs image-remove px-2" data-action="{{ route('parceiros.imageRemove', ['image' => $image->id]) }}"><i class="nav-icon fas fa-times"></i> </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div> 
    </div>
    
    <div class="tab-pane fade" id="custom-tabs-four-seo" role="tabpanel" aria-labelledby="custom-tabs-four-seo-tab">
        <div class="row mb-2 text-muted">
            <div class="col-sm-12">
                <div class="form-group">
                    <h5><b>Configurações SEO</b></h5>  
                    <p>Aqui você pode configurar a otimização para as aplicações de Buscas</p>                                          
                </div>
            </div>
            <div class="col-12 mb-1"> 
                <div class="form-group">
                    <label class="labelforms"><b>MetaTags</b></label>
                    <input id="tags_1" class="tags" rows="5" name="metatags" value="{{ old('metatags') ?? $parceiro->metatags }}">
                </div>
            </div>
            <div class="col-12 mb-1">   
                <div class="form-group">
                    <label class="labelforms"><b>Mapa do Google</b> <small class="text-info">(Copie o código de incorporação do Google Maps e cole abaixo)</small></label>
                    <textarea id="inputDescription" class="form-control" rows="5" name="mapa_google">{{ old('mapa_google') ?? $parceiro->mapa_google }}</textarea> 
                </div>                                                     
            </div>
        </div> 
    </div>
    

</div>
<div class="row text-right">
    <div class="col-12 my-3">
        <button type="submit" class="btn btn-lg btn-success"><i class="nav-icon fas fa-check mr-2"></i> Atualizar Agora</button>
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
         /* Foto User Admin */
         .thumb_user_admin{
        border: 1px solid #ddd;
        border-radius: 4px; 
        text-align: center;
        }
        .thumb_user_admin input[type=file]{
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }
        .thumb_user_admin img{
                        
        }
    </style>
    @stop

@section('js')
<!--tags input-->
<script src="{{url(asset('backend/plugins/jquery-tags-input/jquery.tagsinput.js'))}}"></script>
<script src="{{url(asset('backend/assets/js/jquery.mask.js'))}}"></script>
<script>
    $(document).ready(function () { 
        var $Cnpj = $(".cnpjmask");
        $Cnpj.mask('00.000.000/0000-00', {reverse: true});
        var $whatsapp = $(".whatsappmask");
        $whatsapp.mask('(99) 99999-9999', {reverse: false});
        var $telefone = $(".telefonemask");
        $telefone.mask('(99) 9999-9999', {reverse: false});
        var $celularmask = $(".celularmask");
        $celularmask.mask('(99) 99999-9999', {reverse: false});
        var $zipcode = $(".mask-zipcode");
        $zipcode.mask('00.000-000', {reverse: true});
    });
</script> 

<script>
    $(function () { 
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });  

        function readImage() {
            if (this.files && this.files[0]) {
                var file = new FileReader();
                file.onload = function(e) {
                    document.getElementById("preview").src = e.target.result;
                };       
                file.readAsDataURL(this.files[0]);
            }
        }
        document.getElementById("img-input").addEventListener("change", readImage, false);
        
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

        $('#state-dd').on('change', function () {
            var idState = this.value;
            $("#city-dd").html('Carregando...');
            $.ajax({
                url: "{{route('parceiros.fetchCity')}}",
                type: "POST",
                data: {
                    estado_id: idState,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {
                    $('#city-dd').html('<option value="">Selecione a cidade</option>');
                    $.each(res.cidades, function (key, value) {
                        $("#city-dd").append('<option value="' + value
                            .cidade_id + '">' + value.cidade_nome + '</option>');
                    });
                }
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