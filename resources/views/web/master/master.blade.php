<!DOCTYPE html>
<html class="wide wow-animation" lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta name="language" content="pt-br" />  
    <meta name="copyright" content="{{$configuracoes->ano_de_inicio}} - {{$configuracoes->nomedosite}}">    
    
    <meta name="author" content="{{env('DESENVOLVEDOR')}}"/>
    <meta name="designer" content="Renato Montanari">
    <meta name="publisher" content="Renato Montanari">
    <meta name="url" content="{{$configuracoes->dominio}}" />
    <meta name="keywords" content="{{$configuracoes->metatags}}">
    <meta name="distribution" content="web">
    <meta name="rating" content="general">
    <meta name="date" content="Dec 26">

    {!! $head ?? '' !!}

    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- CSS -->
    <link rel="icon" href="{{$configuracoes->getfaveicon()}}" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Montserrat:400,700%7CLato:300,300italic,400,400italic,700,900%7CPlayfair+Display:700italic,900">
    <link rel="stylesheet" href="{{url(asset('frontend/assets/css/bootstrap.css'))}}">
    <link rel="stylesheet" href="{{url(asset('frontend/assets/css/fonts.css'))}}">
    <link rel="stylesheet" href="{{url(asset('frontend/assets/css/style.css'))}}">

    <!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

    @hasSection('css')
        @yield('css')
    @endif 
    
  </head>
  <body>    
    <div class="page">
        <header class="page-head">
            <div class="rd-navbar-wrap">
                <nav class="rd-navbar rd-navbar-corporate-dark" data-layout="rd-navbar-fixed" 
                    data-sm-layout="rd-navbar-fixed" data-md-layout="rd-navbar-fixed" 
                    data-md-device-layout="rd-navbar-fixed" data-lg-layout="rd-navbar-static" 
                    data-lg-device-layout="rd-navbar-fixed" data-xl-layout="rd-navbar-static" 
                    data-xl-device-layout="rd-navbar-static" data-xxl-layout="rd-navbar-static" 
                    data-xxl-device-layout="rd-navbar-static" data-lg-stick-up="true" 
                    data-xl-stick-up="true" data-xxl-stick-up="true" data-lg-stick-up-offset="53px" 
                    data-xl-stick-up-offset="53px" data-xxl-stick-up-offset="53px">
                    <div class="rd-navbar-inner">
                        <div class="rd-navbar-aside">
                        <div class="rd-navbar-aside-toggle" data-custom-toggle=".rd-navbar-aside" data-custom-toggle-disable-on-blur="true"><span></span></div>
                        <div class="rd-navbar-aside-content context-dark">
                            <ul class="rd-navbar-aside-group list-units">
                                <li>
                                    <div class="unit unit-horizontal unit-spacing-xs">
                                        <div class="unit-left">
                                            <span class="novi-icon icon icon-xxs icon-primary fa-envelope-o"></span>
                                        </div>
                                        <div class="unit-body">
                                            <a class="link-light-2 d-inline" href="#">suporte@informaticalivre.com</a>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="unit unit-horizontal unit-spacing-xs">
                                    <div class="unit-left"><span class="novi-icon icon icon-xxs icon-primary fa-whatsapp"></span></div>
                                        <div class="unit-body">
                                            <p class="text-default">(12) 99138-5030</p>
                                        </div>
                                    </div>
                                </li>                       
                            </ul>
                            <div class="rd-navbar-aside-group">
                                <ul class="list-inline list-inline-reset">
                                    <li><a class="icon icon-round icon-gray-dark-filled icon-xxs-smallest fa fa-facebook" href="#"></a></li>
                                    <li><a class="icon icon-round icon-gray-dark-filled icon-xxs-smallest fa fa-twitter" href="#"></a></li>
                                    <li><a class="icon icon-round icon-gray-dark-filled icon-xxs-smallest fa fa-instagram" href="#"></a></li>
                                </ul>
                            </div>
                        </div>
                        </div>
                        <div class="rd-navbar-group rd-navbar-search-wrap">
                        <div class="rd-navbar-panel">
                            <button class="rd-navbar-toggle" data-custom-toggle=".rd-navbar-nav-wrap" data-custom-toggle-disable-on-blur="true"><span></span></button>
                            <a class="rd-navbar-brand brand" href="{{route('web.home')}}">
                                <img src="{{$configuracoes->getLogomarca()}}" alt="{{$configuracoes->nomedosite}}" width="139" height="22"/>
                            </a>
                        </div>
                        <div class="rd-navbar-nav-wrap">
                            <div class="rd-navbar-nav-inner">
                                <div class="rd-navbar-search">
                                    <form class="rd-search" action="search-results.html" method="GET" data-search-live="rd-search-results-live">
                                    <div class="form-wrap">
                                        <label class="form-label" for="rd-search-form-input">Pesquisar...</label>
                                        <input class="form-input" id="rd-search-form-input" type="text" name="s" autocomplete="off">
                                        <div class="rd-search-results-live" id="rd-search-results-live"></div>
                                    </div>
                                    <button class="rd-search-submit" type="submit"></button>
                                    </form>
                                    <button class="rd-navbar-search-toggle" data-rd-navbar-toggle=".rd-navbar-search, .rd-navbar-search-wrap"></button>
                                </div>
                                <ul class="rd-navbar-nav">
                                    <li class="rd-nav-item">
                                        <a class="rd-nav-link" href="{{route('web.quemsomos')}}" title="Informática Livre">Informática Livre</a>
                                    </li>
                                    <li class="rd-nav-item active"><a class="rd-nav-link" href="#">Serviços</a>
                                        <ul class="rd-menu rd-navbar-dropdown">
                                        <li class="rd-dropdown-item"><a class="rd-dropdown-link" href="#">Criação de Sites</a>
                                        </li>
                                        <li class="rd-dropdown-item"><a class="rd-dropdown-link" href="#">Lojas Virtuais</a>
                                        </li>
                                        <li class="rd-dropdown-item"><a class="rd-dropdown-link" href="#">Sistemas Web</a>
                                        </li>
                                        <li class="rd-dropdown-item"><a class="rd-dropdown-link" href="#">Páginas de Captura</a>
                                        </li>
                                        <li class="rd-dropdown-item"><a class="rd-dropdown-link" href="#">Criação de Portais</a>
                                        </li>
                                        </ul>
                                    </li>  
                                    <li class="rd-nav-item">
                                        <a class="rd-nav-link" href="index.php?v=projetos" title="Protifólio">Protifólio</a>
                                    </li>                          
                                    <li class="rd-nav-item">
                                        <a class="rd-nav-link" href="{{route('web.blog.artigos')}}" title="Dicas">Dicas</a>
                                    </li>
                                    <li class="rd-nav-item">
                                        <a class="rd-nav-link" href="{{route('web.atendimento')}}" title="Atendimento">Atendimento</a>
                                    </li>                        
                                </ul>
                            </div>
                        </div>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        
        @yield('content')       

        <section class="section section-60 section-md-100 bg-accent novi-background">
            <div class="container text-center text-lg-start">
                <div class="row row-30 align-items-md-center justify-content-lg-center">
                    <div class="col-lg-8 col-xl-7">
                        <h3>Solicite Agora um Orçamento</h3>
                    </div>
                    <div class="col-lg-4 col-xl-3">
                        <a class="btn btn-xl btn-black-outline" href="{{route('web.orcamento')}}" target="_blank">Quero um Orçamento</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="section section-40 section-md-top-75 section-md-bottom-60 bg-cod-gray novi-background">
            <div class="container text-center text-md-start">
                <div class="row row-30 align-items-md-center justify-content-lg-center justify-content-xl-start">
                    <div class="col-sm-12 col-md-3 col-lg-2 col-xl-2 text-sm-center">
                        <a class="brand" href="{{route('web.home')}}">
                            <img src="{{$configuracoes->getLogomarca()}}" alt="{{$configuracoes->nomedosite}}" width="139" height="22"/>
                        </a>
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-7 col-xl-6">
                        <div class="wrap-justify">
                            <address class="contact-info text-start">
                                <div class="unit unit-horizontal unit-spacing-xs align-items-center justify-content-center unit-sm-left">
                                    <div class="unit-left">
                                        <span class="novi-icon icon icon-md-custom icon-gunsmoke material-icons-place"></span>
                                    </div>
                                    <div class="unit-body fw-light">
                                        <a class="link-light-03 d-inline" href="javascript:void(0)">Rua Primavera, 120<br>Jardim Carolina - Ubatuba/SP</a>
                                    </div>
                                </div>
                            </address>
                            <address class="contact-info text-start">
                                <div class="unit unit-horizontal unit-spacing-xs align-items-center justify-content-center unit-sm-left">
                                    <div class="unit-left">
                                        <span class="novi-icon icon icon-md-custom icon-gunsmoke material-icons-phone"></span>
                                    </div>
                                    <div class="unit-body fw-light">
                                        <div class="link-wrap"><a class="link-light-03" href="tel:12991385030">(12) 99138-5030</a></div>
                                        <div class="link-wrap"><a class="link-light-03" href="mailto:suporte@informaticalivre.com">suporte@informaticalivre.com</a></div>
                                    </div>
                                </div>
                            </address>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12 col-lg-3 col-xl-4 text-lg-center">
                        <ul class="list-inline list-inline-xs">
                            @if ($configuracoes->facebook)
                                <li><a target="_blank" class="novi-icon icon icon-sm-custom link-tundora fa-facebook" href="{{$configuracoes->facebook}}"></a></li>
                            @endif
                            @if ($configuracoes->twitter)
                                <li><a target="_blank" class="novi-icon icon icon-sm-custom link-tundora fa-twitter" href="{{$configuracoes->twitter}}"></a></li>
                            @endif
                            @if ($configuracoes->instagram)
                                <li><a target="_blank" class="novi-icon icon icon-sm-custom link-tundora fa-instagram" href="{{$configuracoes->instagram}}"></a></li>
                            @endif
                            @if ($configuracoes->linkedin)
                                <li><a target="_blank" class="novi-icon icon icon-sm-custom link-tundora fa-linkedin" href="{{$configuracoes->linkedin}}"></a></li>
                            @endif
                            @if ($configuracoes->youtube)
                                <li><a target="_blank" class="novi-icon icon icon-sm-custom link-tundora fa-youtube" href="{{$configuracoes->youtube}}"></a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <footer class="page-foot page-foot-default section-35 bg-black novi-background text-center">
            <div class="container">
                <p class="rights small">
                    <span>{{$configuracoes->nomedosite}}</span><span>&nbsp;&#169;&nbsp;</span>
                    <span class="copyright-year"></span>
                    <span>Todos os direitos reservados</span><br class="d-md-none">
                    <a class="link-primary" href="{{route('web.politica')}}">Política de Privacidade</a>
                </p>
            </div>
        </footer>

    </div>

    <script src="{{url(asset('frontend/assets/js/core.min.js'))}}"></script>
    <script src="{{url(asset('frontend/assets/js/script.js'))}}"></script> 

    @hasSection('js')
        @yield('js')
    @endif

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-N886VV2RRF"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-N886VV2RRF');
    </script>    
</body>
</html>