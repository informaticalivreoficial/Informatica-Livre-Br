@extends('adminlte::page')

@section('title', 'Configurações')

@php
$config1 = [
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
        <h1 class="m-0 text-dark">Configurações</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item active">Configurações</li>
        </ol>
    </div><!-- /.col -->
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

<form action="{{ route('configuracoes.update', $config->id) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{$config->id }}">
    <div class="row">            
        <div class="col-12">
            <div class="card card-teal card-outline card-outline-tabs">

                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">INFORMAÇÕES GERAIS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">REDES SOCIAIS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">INFORMAÇÕES DE CONTATO</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-mapas-tab" data-toggle="pill" href="#custom-tabs-four-mapas" role="tab" aria-controls="custom-tabs-four-mapas" aria-selected="false">MAPAS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-imagens-tab" data-toggle="pill" href="#custom-tabs-four-imagens" role="tab" aria-controls="custom-tabs-four-imagens" aria-selected="false">IMAGENS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-four-seo-tab" data-toggle="pill" href="#custom-tabs-four-seo" role="tab" aria-controls="custom-tabs-four-mapas" aria-selected="false">SEO</a>
                        </li>                        
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">

                            <div class="row mb-2 text-muted">
                                <div class="col-sm-12 bg-gray-light">                                        
                                    <!-- checkbox -->
                                    <div class="form-group p-3 mb-0">
                                        <h5 class="mr-3"><b>Informações Gerais</b></h5>  
                                        <p>{{ \Illuminate\Support\Facades\Auth::user()->name }} aqui você pode configurar as informações do sistema.</p>                                          
                                    </div>
                                </div>
                            </div>

                            <div class="row">                                        
                                <div class="col-12 col-md-6 col-lg-12"> 
                                    <div class="row mb-2">
                                        <div class="col-12 col-md-6 col-sm-6 col-lg-6 mb-2">
                                            <div class="form-group">
                                                <label class="labelforms text-muted"><b>Nome do site</b></label>
                                                <input type="text" class="form-control text-muted" placeholder="Nome do site" name="nomedosite" value="{{ old('nomedosite') ?? $config->nomedosite }}">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6 col-sm-6 col-lg-6 mb-2">
                                            @if(\Illuminate\Support\Facades\Auth::user()->superadmin == 1)
                                                <div class="form-group">
                                                    <label class="labelforms text-muted"><b>URL do site</b></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-muted" placeholder="URL do site" name="dominio" value="{{ old('dominio') ?? $config->dominio }}"/>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                            <a href="javascript:void(0)" title="QrCode" data-toggle="modal" data-target="#modal-qrcode"><i class="fa fa-qrcode"></i></a>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="form-group">
                                                    <label class="labelforms text-muted"><b>URL do site</b></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control text-muted" placeholder="URL do site" name="dominio" value="{{ old('dominio') ?? $config->dominio }}" disabled>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                            <a href="javascript:void(0)" title="QrCode" data-toggle="modal" data-target="#modal-qrcode"><i class="fa fa-qrcode"></i></a>
                                                            </div>                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif                                                    
                                        </div>                                         
                                    </div>                                           
                                </div>
                            </div>
                           

                            <div id="accordion">                                        
                                <div class="card">
                                    <div class="card-header">
                                        <h4>
                                            <a style="border:none;color: #555;" data-toggle="collapse" data-parent="#accordion" href="#collapseEndereco">
                                                <i class="nav-icon fas fa-plus mr-2"></i> Endereço
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseEndereco" class="panel-collapse collapse show">
                                        <div class="card-body text-muted">
                                            <div class="row mb-2">
                                                <div class="col-12 col-md-4 col-lg-4"> 
                                                    <div class="form-group">
                                                        <label class="labelforms"><b>Estado:</b></label>
                                                        <select id="state-dd" class="form-control text-muted" name="uf">
                                                            @if(!empty($estados))
                                                                <option value="">Selecione o Estado</option>
                                                                @foreach($estados as $estado)
                                                                    <option value="{{$estado->estado_id}}" {{ (old('uf') == $estado->estado_id ? 'selected' : ($config->uf == $estado->estado_id ? 'selected' : '')) }}>{{$estado->estado_nome}}</option>
                                                                @endforeach                                                                        
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4 col-lg-4"> 
                                                    <div class="form-group">
                                                        <label class="labelforms"><b>Cidade:</b></label>
                                                        <select id="city-dd" class="form-control text-muted" name="cidade">
                                                            @if(!empty($cidades) && !empty($config->cidade) && $config->uf != null)
                                                                @foreach($cidades as $cidade)
                                                                <option value="{{$cidade->cidade_id}}" 
                                                                        {{ (old('cidade') == $cidade->cidade_id ? 'selected' : 
                                                                        ($config->cidade == $cidade->cidade_id ? 'selected' : '')) }}>{{$cidade->cidade_nome}}</option>
                                                                @endforeach
                                                            @else  
                                                            <option value="">Selecione o Estado</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4 col-lg-4"> 
                                                    <div class="form-group">
                                                        <label class="labelforms"><b>Bairro:</b></label>
                                                        <input type="text" class="form-control text-muted" placeholder="Bairro" name="bairro" value="{{old('bairro') ?? $config->bairro}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-12 col-md-6 col-lg-5"> 
                                                    <div class="form-group">
                                                        <label class="labelforms"><b>Endereço:</b></label>
                                                        <input type="text" class="form-control text-muted" placeholder="Endereço Completo" name="rua" value="{{old('rua') ?? $config->rua}}">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-2"> 
                                                    <div class="form-group">
                                                        <label class="labelforms"><b>Número:</b></label>
                                                        <input type="text" class="form-control text-muted" placeholder="Número do Endereço" name="num" value="{{old('num') ?? $config->num}}">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-3"> 
                                                    <div class="form-group">
                                                        <label class="labelforms"><b>Complemento:</b></label>
                                                        <input type="text" class="form-control text-muted" placeholder="Completo (Opcional)" name="complemento" value="{{old('complemento') ?? $config->complemento}}">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-2"> 
                                                    <div class="form-group">
                                                        <label class="labelforms"><b>CEP:</b></label>
                                                        <input type="text" class="form-control text-muted mask-zipcode" placeholder="Digite o CEP" name="cep" value="{{old('cep') ?? $config->cep}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header text-muted">
                                        <h4>
                                            <a style="border:none;color: #555;" data-toggle="collapse" data-parent="#accordion" href="#collapseDocs">
                                                <i class="nav-icon fas fa-plus mr-2"></i> Informações da Empresa
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseDocs" class="panel-collapse collapse show">
                                        <div class="card-body text-muted">
                                            <div class="row mb-2">                                                        
                                                <div class="col-12 col-md-4 col-sm-4 col-lg-4"> 
                                                    <div class="form-group">
                                                        <label class="labelforms"><b>CNPJ:</b></label>
                                                        <input type="text" class="form-control text-muted cnpjmask" placeholder="CNPJ" name="cnpj" value="{{old('cnpj') ?? $config->cnpj}}">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4 col-sm-4 col-lg-4"> 
                                                    <div class="form-group">
                                                        <label class="labelforms"><b>Inscrição Estadual:</b></label>
                                                        <input type="text" class="form-control text-muted" placeholder="Inscrição Estadual" name="ie" value="{{old('ie') ?? $config->ie}}">
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-4 col-sm-4 col-lg-4"> 
                                                    <div class="form-group">
                                                        <label class="labelforms"><b>Ano de ínicio</b></label>
                                                        <input type="text" class="form-control text-muted" placeholder="Ano de ínicio" name="ano_de_inicio" value="{{old('ano_de_inicio') ?? $config->ano_de_inicio}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                
                                <div class="row mb-2">
                                    <div class="col-12">   
                                        <label class="labelforms text-muted"><b>Política de Privacidade</b></label>
                                        <x-adminlte-text-editor name="politicas_de_privacidade" v placeholder="Política de Privacidade..." :config="$config1">{{ old('politicas_de_privacidade') ?? $config->politicas_de_privacidade }}</x-adminlte-text-editor>                                                                                     
                                    </div>                                    
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
                                        <input type="text" class="form-control text-muted" placeholder="Facebook" name="facebook" value="{{old('facebook') ?? $config->facebook}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Twitter:</b></label>
                                        <input type="text" class="form-control text-muted" placeholder="Twitter" name="twitter" value="{{old('twitter') ?? $config->twitter}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Youtube:</b></label>
                                        <input type="text" class="form-control text-muted" placeholder="Youtube" name="youtube" value="{{old('youtube') ?? $config->youtube}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Flickr:</b></label>
                                        <input type="text" class="form-control text-muted" placeholder="Flickr" name="fliccr" value="{{old('fliccr') ?? $config->fliccr}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Instagram:</b></label>
                                        <input type="text" class="form-control text-muted" placeholder="Instagram" name="instagram" value="{{old('instagram') ?? $config->instagram}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Vimeo:</b></label>
                                        <input type="text" class="form-control text-muted" placeholder="Vimeo" name="vimeo" value="{{old('vimeo') ?? $config->vimeo}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Linkedin:</b></label>
                                        <input type="text" class="form-control text-muted" placeholder="Linkedin" name="linkedin" value="{{old('linkedin') ?? $config->linkedin}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Sound Cloud:</b></label>
                                        <input type="text" class="form-control text-muted" placeholder="Linkedin" name="soundclound" value="{{old('soundclound') ?? $config->soundclound}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>SnapChat:</b></label>
                                        <input type="text" class="form-control text-muted" placeholder="SnapChat" name="snapchat" value="{{old('snapchat') ?? $config->snapchat}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">                                    
                            <div class="row mb-2">
                                <div class="col-sm-12 text-muted">
                                    <div class="form-group">
                                        <h5><b>Informações de Contato</b></h5>  
                                        <p>Aqui você pode configurar as informações de contato da sua aplicação.</p>                                          
                                    </div>
                                </div>
                                <hr>
                                <div class="col-12 col-md-6 col-lg-4"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Telefone 1:</b></label>
                                        <input type="text" class="form-control text-muted" placeholder="Telefone 1 com DDD" name="telefone1" value="{{old('telefone1') ?? $config->telefone1}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Telefone 2:</b></label>
                                        <input type="text" class="form-control text-muted" placeholder="Telefone 2 com DDD" name="telefone2" value="{{old('telefone2') ?? $config->telefone2}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Telefone 3:</b></label>
                                        <input type="text" class="form-control text-muted" placeholder="Telefone 3 com DDD" name="telefone3" value="{{old('telefone3') ?? $config->telefone3}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>WhatsApp:</b></label>
                                        <input type="text" class="form-control text-muted whatsappmask" placeholder="Número do Celuler com DDD" name="whatsapp" value="{{old('whatsapp') ?? $config->whatsapp}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Email:</b></label>
                                        <input type="text" class="form-control text-muted" placeholder="Email" name="email" value="{{old('email') ?? $config->email}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Email Adicional:</b></label>
                                        <input type="text" class="form-control text-muted" placeholder="Email Alternativo" name="email1" value="{{old('email1') ?? $config->email1}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-4"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Skype:</b></label>
                                        <input type="text" class="form-control text-muted" placeholder="Usuário Skype" name="skype" value="{{old('skype') ?? $config->skype}}">
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-mapas" role="tabpanel" aria-labelledby="custom-tabs-four-mapas-tab">
                            <div class="row mb-2 text-muted">                                        
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <h5><b>Mapa do Google</b></h5>  
                                        <p>Aqui você pode configurar o mapa de endereço para o site.</p>                                          
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-sm-6 col-lg-12 mapa-google mb-3"> 
                                    {!! old('mapa_google') ?? $config->mapa_google !!}
                                </div>
                                <div class="col-12 col-md-6 col-sm-6 col-lg-12">   
                                    <div class="form-group">
                                        <label class="labelforms"><b>Mapa do Google</b> <small class="text-info">(Copie o código de incorporação do Google Maps e cole abaixo)</small></label>
                                        <textarea id="inputDescription" class="form-control" rows="5" name="mapa_google">{{ old('mapa_google') ?? $config->mapa_google }}</textarea> 
                                    </div>                                                     
                                </div>                                         
                            </div> 
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-four-imagens" role="tabpanel" aria-labelledby="custom-tabs-four-imagens-tab">
                            <div class="row mb-2 text-muted">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <h5><b>Imagens do site</b></h5>  
                                        <p>Aqui você configurar as imagens do site, fique atento ao tamanho das imagens para uma melhor experiência da sua aplicação.</p>                                          
                                    </div>
                                </div>
                                <hr>
                                <div class="col-12 col-md-6 col-sm-6 col-lg-6"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Logomarca do site</b> - {{env('LOGOMARCA_WIDTH')}}x{{env('LOGOMARCA_HEIGHT')}} pixels</label>
                                        <div class="thumb_user_admin">                                                    
                                            <img id="preview2" width="{{env('LOGOMARCA_WIDTH')}}" height="{{env('LOGOMARCA_HEIGHT')}}" src="{{$config->getlogomarca()}}" alt="{{ old('dominio') ?? $config->dominio }}" title="{{ old('dominio') ?? $config->dominio }}"/>
                                            <input id="img-logomarca" type="file" name="logomarca">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-sm-6 col-lg-6"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Logomarca do Gerenciador</b> - {{env('LOGOMARCA_GERENCIADOR_WIDTH')}}x{{env('LOGOMARCA_GERENCIADOR_HEIGHT')}} pixels</label>
                                        <div class="thumb_user_admin">                                                    
                                            <img id="preview3" width="{{env('LOGOMARCA_GERENCIADOR_WIDTH')}}" height="{{env('LOGOMARCA_GERENCIADOR_HEIGHT')}}" src="{{$config->getlogoadmin()}}" alt="{{ old('dominio') ?? $config->dominio }}" title="{{ old('dominio') ?? $config->dominio }}"/>
                                            <input id="img-logomarcaadmin" type="file" name="logomarca_admin">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-sm-6 col-lg-6"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Favicon</b> - {{env('FAVEICON_WIDTH')}}x{{env('FAVEICON_HEIGHT')}} pixels</label>
                                        <div class="thumb_user_admin">                                                    
                                            <img id="preview4" width="{{env('FAVEICON_WIDTH')}}" height="{{env('FAVEICON_HEIGHT')}}" src="{{$config->getfaveicon()}}" alt="{{ old('dominio') ?? $config->dominio }}" title="{{ old('dominio') ?? $config->dominio }}"/>
                                            <input id="img-favicon" type="file" name="favicon">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-sm-6 col-lg-6"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Marca D´agua</b> - {{env('MARCADAGUA_WIDTH')}}x{{env('MARCADAGUA_HEIGHT')}} pixels</label>
                                        <div class="thumb_user_admin">                                                    
                                            <img id="preview5" width="{{env('MARCADAGUA_WIDTH')}}" height="{{env('MARCADAGUA_HEIGHT')}}" src="{{$config->getmarcadagua()}}" alt="{{ old('dominio') ?? $config->dominio }}" title="{{ old('dominio') ?? $config->dominio }}"/>
                                            <input id="img-marcadagua" type="file" name="marcadagua">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Topo do site</b> - {{env('IMGHEADER_WIDTH')}}x{{env('IMGHEADER_HEIGHT')}} pixels</label>
                                        <div class="thumb_user_admin">
                                            <img id="preview6" width="{{env('IMGHEADER_WIDTH')}}" height="{{env('IMGHEADER_HEIGHT')}}" src="{{$config->gettopodosite()}}" alt="{{ old('dominio') ?? $config->dominio }}" title="{{ old('dominio') ?? $config->dominio }}"/>
                                            <input id="img-imgheader" type="file" name="imgheader">
                                        </div>
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
                                <div class="col-12 mb-1 acoes text-right py-2"> 
                                    <a data-id="{{$config->id}}" href="javascript:void(0)" class="btn {{ ($diferenca >= 30 ? 'btn-warning' : 'btn-success disabled') }} btn-flat btn_sitemap">{!! ($diferenca >= 30 ? '<i class="fas fa-exclamation-triangle"></i> Sitemap Desatualizado' : '<i class="fas fa-check"></i> Sitemap Atualizado') !!}</a>
                                </div>
                                <div class="col-12 mb-1"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Descrição do site</b></label>
                                        <textarea class="form-control" rows="5" name="descricao">{{ old('descricao') ?? $config->descricao }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12 mb-1"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>MetaTags</b></label>
                                        <input id="tags_1" class="tags" rows="5" name="metatags" value="{{ old('metatags') ?? $config->metatags }}">
                                    </div>
                                </div>
                                <div class="col-12 mb-1"> 
                                    <div class="form-group">
                                        <label class="labelforms"><b>Meta Imagem: </b>(800X418) pixels</label>
                                        <div class="thumb_user_admin">                                                    
                                            <img id="preview1" src="{{$config->getmetaimg()}}" alt="{{ old('dominio') ?? $config->dominio }}" title="{{ old('dominio') ?? $config->dominio }}"/>
                                            <input id="img-input" type="file" name="metaimg">
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>                                
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>

    <div class="row text-right">
        <div class="col-12 mb-4">
            <button type="submit" class="btn btn-success btn-lg"><i class="nav-icon fas fa-check mr-2"></i> Atualizar Configurações</button>
        </div>
    </div> 
</form>

@stop

@section('css')
<!--tags input-->
<link rel="stylesheet" href="{{url(asset('backend/plugins/jquery-tags-input/jquery.tagsinput.css'))}}" />
<style>
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
		 max-width: 100%;          
	}
</style>
@stop

@section('plugins.Toastr', true)


@section('js')
<!--tags input-->
<script src="{{url(asset('backend/plugins/jquery-tags-input/jquery.tagsinput.js'))}}"></script>
<script src="{{url(asset('backend/assets/js/jquery.mask.js'))}}"></script>
<script>
    $(document).ready(function () { 
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

        // BOTÃO SITEMAP
        $('.btn_sitemap').click(function(){ 
            //var button = $(this);
            var conf_id = $(this).data('id'); 
            $.ajax({
                type: 'GET',
                dataType: 'JSON',
                url: "{{ route('admin.gerarxml') }}",
                data: {
                   'id': conf_id
                },
                beforeSend: function(){
                    $('.btn_sitemap').html("Carregando...");                        
                },
                complete: function(){
                    $('.btn_sitemap').html("<i class=\"fas fa-check\"></i> Sitemap Atualizado");               
                },
                success:function(response) {
                    if (response.success === true) {
                        $('.btn_sitemap').removeClass('btn-warning');
                        $('.btn_sitemap').addClass('btn-success');
                        $('.btn_sitemap').addClass('disabled');                
                        toastr.success('Sitemap Atualizado');                            
                    }else{
                        toastr.error('Erro ao atualizar!'); 
                    }
                }
            });
            return false;
        });

        // FORM DE SUPORTE NO ADMIN
        $('.btn_suporte').submit(function(){ 
            var dados = $(this).serialize();                
            $.ajax({
                type: 'GET',
                dataType: 'JSON',
                url: "{{ route('email.suporte') }}",
                data: dados,
                beforeSend: function(){
                    $('.b_nome').html("Carregando...");
                    $('.alert').fadeOut(500, function(){
                        $(this).remove();
                    });
                },
                complete: function(){
                    $('.b_nome').html("<i class=\"nav-icon fas fa-check mr-2\"></i> Enviar Solicitação");               
                },
                success:function(data) {
                    if(data.error){
                        $('.j_param_data').html('<div class="alert alert-danger alert-dismissible">'+ data.error +'</div>');
                    }else{
                        $('input[class!="noclear"]').val('');
                        $('.form_hide').fadeOut(500);
                        $('.j_param_data').html('<div class="alert alert-success alert-dismissible">'+ data.success +'</div>');
                    }
                }
            });
            return false;
        });
        
        function readImageMetaImagem() {
            if (this.files && this.files[0]) {
                var file = new FileReader();
                file.onload = function(e) {
                    document.getElementById("preview1").src = e.target.result;
                };       
                file.readAsDataURL(this.files[0]);
            }
        }
        
        function readImageLogomarca() {
            if (this.files && this.files[0]) {
                var file = new FileReader();
                file.onload = function(e) {
                    document.getElementById("preview2").src = e.target.result;
                };       
                file.readAsDataURL(this.files[0]);
            }
        }
        function readImageLogoAdmin() {
            if (this.files && this.files[0]) {
                var file = new FileReader();
                file.onload = function(e) {
                    document.getElementById("preview3").src = e.target.result;
                };       
                file.readAsDataURL(this.files[0]);
            }
        }
        function readImageFavicon() {
            if (this.files && this.files[0]) {
                var file = new FileReader();
                file.onload = function(e) {
                    document.getElementById("preview4").src = e.target.result;
                };       
                file.readAsDataURL(this.files[0]);
            }
        }
        function readImageMarcadagua() {
            if (this.files && this.files[0]) {
                var file = new FileReader();
                file.onload = function(e) {
                    document.getElementById("preview5").src = e.target.result;
                };       
                file.readAsDataURL(this.files[0]);
            }
        }
        function readImageImgheader() {
            if (this.files && this.files[0]) {
                var file = new FileReader();
                file.onload = function(e) {
                    document.getElementById("preview6").src = e.target.result;
                };       
                file.readAsDataURL(this.files[0]);
            }
        }
        document.getElementById("img-input").addEventListener("change", readImageMetaImagem, false);
        document.getElementById("img-logomarca").addEventListener("change", readImageLogomarca, false);
        document.getElementById("img-logomarcaadmin").addEventListener("change", readImageLogoAdmin, false);
        document.getElementById("img-favicon").addEventListener("change", readImageFavicon, false);
        document.getElementById("img-marcadagua").addEventListener("change", readImageMarcadagua, false);
        document.getElementById("img-imgheader").addEventListener("change", readImageImgheader, false);
                    
       
        $('#state-dd').on('change', function () {
            var idState = this.value;
            $("#city-dd").html('');
            $.ajax({
                url: "{{route('configuracoes.fetchCity')}}",
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