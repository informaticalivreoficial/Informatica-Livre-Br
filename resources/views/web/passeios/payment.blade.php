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
                <h1 class="text-bold">Comprar passeio</h1>
            </div>
            <ul class="list-inline list-inline-icon list-inline-icon-type-1 list-inline-icon-extra-small list-inline-icon-white p offset-top-30 offset-md-top-40">
                <li><a href="{{route('web.home')}}" class="text-white">Início</a></li>
                <li><a href="{{route('web.roteiros')}}" class="text-white">Roteiros</a></li>
                <li>Comprar passeio</li>
            </ul>
            </div>
        </div>
        </div>
    </section>  
</header>

<main class="page-content">
<!-- Login and Register-->
<section class="section-top-90 section-bottom-90 section-md-top-82 section-md-bottom-100 section-md-111 text-sm-left">
    <div class="shell">
    <div class="range range-xs-center range-md-left">
        <div class="cell-12">        
            @if(session()->exists('message'))
                <div class="alert alert-{{ session()->get('color') }} alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session()->get('message') }}
                </div>                
            @endif
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