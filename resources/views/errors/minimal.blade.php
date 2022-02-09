<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

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
        
    </head>
    <body>
        <div class="page">
            <header class="section-single-header">
                <div class="container">
                    <a href="{{route('web.home')}}">
                        <img src="{{$configuracoes->getLogomarca()}}" alt="{{$configuracoes->nomedosite}}" width="139" height="22"/>
                    </a>
                </div>
            </header>
            @yield('content-error')
        </div>
    </body>
</html>
