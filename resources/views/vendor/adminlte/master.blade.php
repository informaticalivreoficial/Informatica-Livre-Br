<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        Gerenciador - {{$configuracoes->nomedosite}}
        {{--@yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))--}}
    </title>

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')

    {{-- Base Stylesheets --}}
    @if(!config('adminlte.enabled_laravel_mix'))
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

        {{-- Configured Stylesheets --}}
        @include('adminlte::plugins', ['type' => 'css'])

        <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    @else
        <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_mix_css_path', 'css/app.css')) }}">
    @endif

    {{-- Livewire Styles --}}
    @if(config('adminlte.livewire'))
        @if(app()->version() >= 7)
            @livewireStyles
        @else
            <livewire:styles />
        @endif
    @endif

    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')

    {{-- Favicon --}}
    @if(config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.png') }}" />
    @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel="manifest" href="{{ asset('favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    @endif

</head>

<body class="@yield('classes_body')" @yield('body_data')>

    {{-- Body Content --}}
    @yield('body')

    <div class="modal fade" id="modal-suporte">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <form class="btn_suporte" method="post" action="" autocomplete="off">
                    @csrf 
                    <div class="col-sm-12 form_hide">
                        <div class="form-group">
                            <h5><b>Suporte ao Cliente</b></h5>  
                            <p>Digite sua solicitação de suporte ou dúvida no campo abaixo. Iremos atender o mais breve possível.</p>                                          
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <span class="j_param_data"></span>
                    </div>
                    <hr>
                    <div class="col-sm-12 mt-2 form_hide">
                        <div class="form-group">
                            <input type="hidden" name="username" value="{{ auth()->user()->name ?? '' }}"/>
                            <input type="hidden" name="sitename" value="{{ $configuracoes->nomedosite ?? ''}}"/>
                            <input type="hidden" name="email" value="{{ auth()->user()->email ?? '' }}"/>
                            <textarea class="form-control noclear" rows="5" name="mensagem"></textarea>                                          
                        </div>
                    </div>
                    <div class="col-12 mb-4 form_hide">
                        <button type="submit" class="btn btn-success b_nome"><i class="nav-icon fas fa-check mr-2"></i> Enviar Solicitação</button>
                    </div>
                </form>            
            </div>
        </div>
    </div>

    {{-- Base Scripts --}}
    @if(!config('adminlte.enabled_laravel_mix'))
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script type="application/javascript" src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

        {{-- Configured Scripts --}}
        @include('adminlte::plugins', ['type' => 'js'])

        <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @else
        <script src="{{ mix(config('adminlte.laravel_mix_js_path', 'js/app.js')) }}"></script>
    @endif
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- Livewire Script --}}
    @if(config('adminlte.livewire'))
        @if(app()->version() >= 7)
            @livewireScripts
        @else
            <livewire:scripts />
        @endif
    @endif

    {{-- Custom Scripts --}}
    @yield('adminlte_js')

    <script>
        $(function () { 
            
    
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
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
        });
    </script>

</body>

</html>
