<!DOCTYPE html>
<html lang="pt-br" class="wide wow-animation smoothscroll scrollTo">
  <head>
    <meta charset="utf-8">
    <meta name="language" content="pt-br" />  
    <meta name="robots" content="index, follow"/>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    
    <meta name="author" content="Informática Livre"/>
    <meta name="url" content="https://aquamarineturismonautico.com.br" />
    <meta name="keywords" content="{{$configuracoes->metatags}}">
    <meta name="description" content="Venha desfrutar de momentos inesquecíveis a bordo das Escunas da Aquá Marine Turismo Náutico. Nossas escunas são altamente seguras e confortáveis. Contam com serviço de bar a  bordo.Nosso Roteiro principal é a Ilha Anchieta e o passeio tem duração de 5 a 5 hrs e meia com paradas para mergulho.*Para outros roteiros consulte nos."/>
    <meta name="date" content="Dec 26">

    {!! $head ?? '' !!}

    <!-- FAVICON -->
    <link rel="shortcut icon" href="{{$configuracoes->getfaveicon()}}"/>
    <link rel="apple-touch-icon" href="{{$configuracoes->getfaveicon()}}"/>
    <link rel="apple-touch-icon" sizes="72x72" href="{{$configuracoes->getfaveicon()}}"/>
    <link rel="apple-touch-icon" sizes="114x114" href="{{$configuracoes->getfaveicon()}}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:400,400italic,700%7CLato:400">
    <link rel="stylesheet" href="{{url(asset('frontend/assets/css/style.css'))}}">
    <link rel="stylesheet" href="{{url(asset('frontend/assets/css/renato.css'))}}">
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="{{url(asset('backend/plugins/ekko-lightbox/ekko-lightbox.css'))}}"/>
    <!--[if lt IE 10]>
        <div style="background: #212121; padding: 10px 0; box-shadow: 3px 3px 5px 0 rgba(0,0,0,.3); clear: both; text-align:center; position: relative; z-index:1;"><a href="https://windows.microsoft.com/en-US/internet-explorer/"><img src="images/ie8-panel/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today."></a></div>
        <script src="{{url(asset('frontend/assets/js/html5shiv.min.js'))}}"></script>
    <![endif]-->

    @hasSection('css')
        @yield('css')
    @endif 
    
  </head>
  <body>    
    <div class="page text-center">
        
    @yield('content')       

    <!-- Footer-->
    <footer class="page-footer bg-chathams-blue">
        <section class="section-60">
            <div class="shell">
            <div class="range range-xs-center text-md-left">
                <div class="cell-xs-10 cell-sm-7 cell-md-4">
                <!-- Footer brand-->
                <div class="footer-brand"><a href="index.html">
                    <img src="{{$configuracoes->getlogomarca()}}" alt="{{$configuracoes->nomedosite}}" title="{{$configuracoes->nomedosite}}"/></a>
                </div>
                <div class="offset-top-30 inset-sm-right-20 text-silver">                    
                    {!!$configuracoes->descricao!!}
                    @if($configuracoes->cnpj)
                     <p>CNPJ: {{$configuracoes->cnpj}}</p>
                    @endif                                        
                    @if($configuracoes->ie)
                     <p>IE: {{$configuracoes->ie}}</p>
                    @endif                                        
                </div>
                </div>
                <div class="cell-xs-10 cell-sm-7 cell-md-5 offset-top-60 offset-md-top-0">
                <div>
                    <h5 class="text-bold text-white">Atendimento</h5>
                </div>
                <div class="offset-top-6">
                    <div class="text-subline bg-ripe-lemon"></div>
                </div>
                <div class="offset-top-20">
                    <!-- Contact Info-->
                    <address class="contact-info text-left">
                    <div>
                        <div class="reveal-inline-block">
                            @if ($configuracoes->whatsapp)
                                <a href="{{getNumZap($configuracoes->whatsapp ,'Atendimento Aqua Marine Turismo Náutico')}}" class="unit unit-middle unit-horizontal unit-spacing-xs">
                                    <span class="unit-left">
                                        <span style="background-color: #34af23;color:#fff;" class="icon icon-xxs icon-circle mdi mdi-whatsapp"></span>
                                    </span>
                                    <span class="unit-body">
                                        <span class="text-silver">{{$configuracoes->whatsapp}}</span>
                                    </span>
                                </a>
                            @endif
                            @if ($configuracoes->telefone1)
                                <a href="callto:{{$configuracoes->telefone1}}" class="unit unit-middle unit-horizontal unit-spacing-xs ">
                                    <span class="unit-left">
                                        <span class=" offset-top-10 icon icon-xxs icon-primary icon-primary-filled icon-circle mdi mdi-phone"></span>
                                    </span>
                                    <span class="unit-body">
                                        <span class="text-silver">{{$configuracoes->telefone1}}</span>
                                        @if ($configuracoes->telefone2)
                                            - {{$configuracoes->telefone2}}
                                        @endif
                                    </span>
                                </a>
                            @endif
                            
                        </div>
                    </div>
                    @if ($configuracoes->email)
                        <div class="offset-top-10">
                            <div class="reveal-inline-block">
                                <a href="mailto:{{$configuracoes->email}}" class="unit unit-middle unit-horizontal unit-spacing-xs">
                                    <span class="unit-left">
                                        <span class="icon icon-xxs icon-primary icon-primary-filled icon-circle mdi mdi-email-outline"></span>
                                    </span>
                                    <span class="unit-body">
                                        <span class="text-silver">{{$configuracoes->email}}</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if ($configuracoes->email1)
                        <div class="offset-top-10">
                            <div class="reveal-inline-block">
                                <a href="mailto:{{$configuracoes->email1}}" class="unit unit-middle unit-horizontal unit-spacing-xs">
                                    <span class="unit-left">
                                        <span class="icon icon-xxs icon-primary icon-primary-filled icon-circle mdi mdi-email-outline"></span>
                                    </span>
                                    <span class="unit-body">
                                        <span class="text-silver">{{$configuracoes->email1}}</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($configuracoes->rua)	
                    <div class="offset-top-10">
                        <div class="reveal-inline-block">
                            <a href="#" class="unit unit-horizontal unit-spacing-xs unit-spacing-xs">
                            <span class="unit-left">
                                <span class="icon icon-xxs icon-primary icon-primary-filled icon-circle mdi mdi-map-marker"></span>
                            </span>
                            <span class="unit-body">
                                <span class="text-silver">{{$configuracoes->rua}}
                                @if($configuracoes->num)
                                , {{$configuracoes->num}}
                                @if($configuracoes->bairro)
                                    , {{$configuracoes->bairro}}
                                @endif
                                @endif
                                </span>
                            </span>
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    </address>
                </div>
                <div class="offset-top-20">
                    <ul class="list-inline list-inline-2">
                        @if ($configuracoes->email)
                            <li><a href="mailto:{{$configuracoes->facebook}}" class="icon icon-xxs icon-silver-filled icon-circle fa fa-envelope"></a></li>
                        @endif
                        @if ($configuracoes->facebook)
                            <li><a target="_blank" href="{{$configuracoes->facebook}}" class="icon icon-xxs icon-silver-filled icon-circle fa fa-facebook"></a></li>
                        @endif
                        @if ($configuracoes->twitter)
                            <li><a target="_blank" href="{{$configuracoes->twitter}}" class="icon icon-xxs icon-silver-filled icon-circle fa fa-twitter"></a></li>
                        @endif
                        @if ($configuracoes->instagram)
                            <li><a target="_blank" href="{{$configuracoes->instagram}}" class="icon icon-xxs icon-silver-filled icon-circle fa fa-instagram"></a></li>
                        @endif                
                    </ul>
                </div>
                </div>
                <div class="cell-xs-10 cell-sm-7 cell-md-3 offset-top-60 offset-md-top-0">
                <div>
                    <h5 class="text-bold text-white">Newsletter</h5>
                </div>
                <div class="offset-top-6">
                    <div class="text-subline bg-ripe-lemon"></div>
                </div>
                <div class="offset-top-25">
                    <p class="text-silver">Cadastre seu email e receba nossas promoções e dicas de passeios.</p>
                </div>
                <div class="offset-top-20">
                    <form method="post" action="" class="rd-mailform-subscribe j_submitnewsletter">
                        @csrf
                        <div id="js-newsletter-result"></div>
                        <!-- HONEYPOT -->
                        <input type="hidden" class="noclear" name="bairro" value="" />
                        <input type="text" class="noclear" style="display: none;" name="cidade" value="" />
                        <input type="hidden" class="noclear" name="categoria" value="1" />
                        <input type="hidden" class="noclear" name="status" value="1" />
                        <input type="hidden" class="noclear" name="nome" value="#Cadastrado pelo Site" />
                        <div class="form-group form-group-sm form_hide">
                            <div class="input-group">
                                <input placeholder="Seu email..." type="email" name="email" class="form-control"><span class="input-group-btn">
                                <button type="submit" class="btn btn-xs btn-ripe-lemon">Cadastrar</button></span>
                            </div>
                        </div>                        
                    </form>
                </div>
                </div>
            </div>
            </div>
        </section>
        <section class="section-12 text-md-left">
            <div class="shell">
            <p class="font-accent">
                <span class="small text-silver-dark">&copy; {{$configuracoes->ano_de_inicio}} {{$configuracoes->nomedosite}} - todos os direitos reservados.</span>
            </p>
            <p class="font-accent">
                <span class="small text-silver-dark">Feito com <i style="color:red;" class="fa fa-heart"></i> por <a style="color:#fff;" target="_blank" href="{{env('DESENVOLVEDOR_URL')}}">{{env('DESENVOLVEDOR')}}</a></span>
            </p>
            </div>
        </section>
    </footer>

    <div class="whatsapp-footer">
        <a href="{{getNumZap($configuracoes->whatsapp ,'Atendimento Aqua Marine Turismo Náutico')}}" title="WhatsApp">
            <img src="{{url(asset('frontend/assets/images/zap-topo.png'))}}" alt="{{url(asset('frontend/assets/images/zap-topo.png'))}}" title="WhatsApp" />
        </a>
    </div>

    </div>
   
    <script src="{{url(asset('frontend/assets/js/core.min.js'))}}"></script>
    <script src="{{url(asset('frontend/assets/js/pointer-events.min.js'))}}"></script>
    <script src="{{url(asset('frontend/assets/js/SmoothScroll.min.js'))}}"></script>
    <script src="{{url(asset('frontend/assets/js/bootstrap.js'))}}"></script>
    <script src="{{url(asset('frontend/assets/js/rdvideoplayer.js'))}}"></script>
    <script src="{{url(asset('frontend/assets/js/moment.js'))}}"></script>
    <script src="{{url(asset('frontend/assets/js/materialdatepicker.js'))}}"></script>
    <script src="{{url(asset('frontend/assets/js/timecircles.js'))}}"></script>
    <!-- Ekko Lightbox -->
    <script src="{{url(asset('backend/plugins/ekko-lightbox/ekko-lightbox.min.js'))}}"></script>
    <script src="{{url(asset('frontend/assets/js/script.js'))}}"></script>  

    @hasSection('js')
        @yield('js')
    @endif

    <script>
        $(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Seletor, Evento/efeitos, CallBack, Ação
            $('.j_submitnewsletter').submit(function (){
                var form = $(this);
                var dataString = $(form).serialize();

                $.ajax({
                    url: '{{ route('web.sendNewsletter') }}',
                    data: dataString,
                    type: 'GET',
                    dataType: 'JSON',
                    beforeSend: function(){
                        form.find("#js-subscribe-btn").attr("disabled", true);
                        form.find('#js-subscribe-btn').html("Carregando...");                
                        form.find('.alert').fadeOut(500, function(){
                            $(this).remove();
                        });
                    },
                    success: function(response){
                        $('html, body').animate({scrollTop:$('#js-newsletter-result').offset().top-40}, 'slow');
                        if(response.error){
                            form.find('#js-newsletter-result').html('<div class="alert alert-danger error-msg">'+ response.error +'</div>');
                            form.find('.error-msg').fadeIn();                    
                        }else{
                            form.find('#js-newsletter-result').html('<div class="alert alert-success error-msg">'+ response.sucess +'</div>');
                            form.find('.error-msg').fadeIn();                    
                            form.find('input[class!="noclear"]').val('');
                            form.find('.form_hide').fadeOut(500);
                        }
                    },
                    complete: function(response){
                        form.find("#js-subscribe-btn").attr("disabled", false);
                        form.find('#js-subscribe-btn').html("Cadastrar!");                                
                    }

                });

                return false;
            });

        });
    </script>

    <script async src="https://www.googletagmanager.com/gtag/js?id=G-C5XNETL9RJ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-C5XNETL9RJ');
    </script>


    <!-- Messenger Plugin de bate-papo Code -->
    <div id="fb-root"></div>

    
</body>
</html>