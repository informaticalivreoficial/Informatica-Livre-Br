@extends('web.master.master')


@section('content')
<header class="page-head slider-menu-position">
    @include('web.include.header')
    
    <section class="section-height-800 breadcrumb-modern rd-parallax context-dark bg-gray-darkest text-lg-left">
        <div data-speed="0.2" data-type="media" data-url="{{url(asset('frontend/assets/images/backgrounds/background-01-1920x900.jpg'))}}" class="rd-parallax-layer"></div>
        <div data-speed="0" data-type="html" class="rd-parallax-layer">
        <div class="bg-primary-chathams-blue-reverse">
            <div class="shell section-top-57 section-bottom-30 section-md-top-185">
            <div class="veil reveal-md-block">
                <h1 class="text-bold">Embarcações</h1>
            </div>
            <ul class="list-inline list-inline-icon list-inline-icon-type-1 list-inline-icon-extra-small list-inline-icon-white p offset-top-30 offset-md-top-40">
                <li><a href="{{route('web.home')}}" class="text-white">Início</a></li>
                <li>Embarcações</li>
            </ul>
            </div>
        </div>
        </div>
    </section>  
</header>

  
<main class="page-content">
    <!-- Where Will You Go?-->
    <section class="section-50 section-md-50 text-left bg-zircon">
    <div class="shell">
        <div class="range range-xs-center">
        <div class="cell-md-12">   
            @if($embarcacoes->count() && $embarcacoes->count() > 0)        
                <div class="range range-xs-center offset-top-30 offset-md-top-60">
                    @foreach ($embarcacoes as $embarcacao)
                        <div class="cell-xs-12 cell-sm-6 cell-md-4 offset-top-30 offset-sm-top-0">
                            <!-- Box Member Type 2-->
                            <div class="box-member box-member-type-2 box-member-modern box-member-caption-offset">
                            <div class="box-member-img-wrap">
                                <img src="{{$embarcacao->cover()}}" width="480" height="550" alt="" class="img-responsive"/>
                            </div>
                            <div class="box-member-wrap">
                                <div class="box-member-caption">
                                    <div class="box-member-caption-inner">
                                        <div class="box-member-caption-wrap">
                                            <div class="box-member-title">
                                                <div class="h4 text-bold text-white">
                                                    <a href="{{route('web.embarcacao', ['slug' => $embarcacao->slug])}}">{{$embarcacao->name}}</a>
                                                </div>
                                            </div>
                                        <div class="box-member-description offset-top-6">
                                            <p></p>
                                        </div>
                                        <div class="offset-top-20">
                                            <a href="{{route('web.embarcacao', ['slug' => $embarcacao->slug])}}" class="btn btn-ripe-lemon">Leia mais</a>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
            <div class="text-center text-md-left offset-top-60">
                <div class="alert alert-info">Desculpe nenhum dado emcontrado!</div>
            </div>
            @endif
            <div class="text-center text-md-left offset-top-60">
                {{ $embarcacoes->links() }}
            </div>
        </div>        
        </div>
    </div>
    </section>
</main>

@endsection

@section('css')

@endsection

@section('js')

@endsection