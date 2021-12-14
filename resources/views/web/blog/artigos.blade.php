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
                <h1 class="text-bold">Blog</h1>
            </div>
            <ul class="list-inline list-inline-icon list-inline-icon-type-1 list-inline-icon-extra-small list-inline-icon-white p offset-top-30 offset-md-top-40">
                <li><a href="{{route('web.home')}}" class="text-white">Início</a></li>
                <li>Blog</li>
            </ul>
            </div>
        </div>
    </div>
</section>
</header>
      
<main class="page-content">
<!-- Blog Classic-->
<div id="fb-root"></div>
<section class="section-90 section-md-111 text-left bg-zircon">
    <div class="shell">
    <div class="range range-xs-center range-lg-right">
        <div class="cell-sm-10 cell-md-8">
        @if($posts->count() && $posts->count() > 0)
            @foreach($posts as $artigo)
            <article class="post post-modern post-modern-timeline post-modern-timeline-left">
                <!-- Post media-->
                <header class="post-media">
                    <a title="{{$artigo->titulo}}" href="{{route('web.blog.artigo',['slug' => $artigo->slug])}}">
                        <img width="570" height="369" src="{{$artigo->cover()}}" alt="{{$artigo->titulo}}" class="img-responsive img-cover"/>
                    </a>
                </header>
                <!-- Post content-->
                <section class="post-content text-left">
                <!-- Post Title-->
                <div class="post-title offset-top-8">
                    <h4 class="text-bold text-ripe-lemon">
                        <a title="{{$artigo->titulo}}" href="{{route('web.blog.artigo',['slug' => $artigo->slug])}}">{{$artigo->titulo}}</a>
                    </h4>
                </div>
                <!-- Post Body-->
                <div class="post-body">
                    <div class="offset-top-15">
                        {!! $artigo->getContentWebAttribute() !!}
                    </div>
                </div>
                <div class="offset-top-20">
                    <div class="tags group group-sm">
                        <a href="{{route('web.blog.categoria', ['slug' => $artigo->categoriaObject->slug] )}}" class="btn-xs btn-tag btn btn-ripe-lemon">{{$artigo->categoriaObject->titulo}}</a>
                    </div>
                </div>
                <div class="post-author">
                    <div class="post-author-img"><img width="80" height="80" src="{{$artigo->userObject->getUrlAvatarAttribute()}}" alt="{{$artigo->userObject->name}}" class="img-circle"/></div>
                    <div class="reveal-inline-block text-middle">
                    <div class="post-author-name">{{$artigo->userObject->name}}
                    </div>
                    <div class="post-meta">
                        {{ \Carbon\Carbon::parse($artigo->publish_at)->diffForHumans() }}
                    </div>
                    </div>
                </div>
                </section>
            </article>
            @endforeach
        @endif   
       
        <div class="text-center text-md-left offset-top-60">
            @if (isset($filters))
                {{ $posts->appends($filters)->links() }}
            @else
                {{ $posts->links() }}
            @endif         
        </div>
        </div>
        <div class="cell-sm-10 cell-md-4 offset-top-60 offset-md-top-0">
        <div class="inset-md-left-30">
            <!-- Aside-->
            <aside class="text-left">
            <!-- Search in Blog-->
            @include('web.include.blog-search')

            <!-- Categories-->
            <div class="offset-top-30 offset-md-top-60">
                <h5 class="text-bold">Categorias</h5>
            </div>
            <div class="offset-top-6">
                <div class="text-subline bg-pizazz"></div>
            </div>
            <div class="offset-top-15 offset-md-top-20">
                <div class="inset-xs-left-8">
                <!-- List Marked-->
                <ul class="list list-marked list-marked-icon text-dark">
                    @if(!empty($categorias) && $categorias->count() > 0)
                        @foreach($categorias as $categoria)                                    
                            @if($categoria->children)
                                @foreach($categoria->children as $subcategoria)
                                    @if($subcategoria->countposts() >= 1)
                                        <li><a class="text-ripe-lemon" href="{{route('web.blog.categoria', ['slug' => $subcategoria->slug] )}}" title="{{ $subcategoria->titulo }}">{{ $subcategoria->titulo }}</a> ({{$subcategoria->countposts()}})</li>
                                    @endif                                            
                                @endforeach
                            @endif                                                                                                                             
                        @endforeach
                    @endif
                </ul>
                </div>
            </div>
            
            <div class="offset-top-30 offset-md-top-60">
                <!-- Facebook standart widget-->
                <div>
                <div class="fb-root fb-widget">
                    <div class="fb-page-responsive">
                    <div data-href="{{$configuracoes->facebook}}" data-tabs="timeline" data-height="500" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" class="fb-page">
                        <div class="fb-xfbml-parse-ignore">
                        <blockquote cite="{{$configuracoes->facebook}}"><a href="{{$configuracoes->facebook}}">{{$configuracoes->nomedosite}}</a></blockquote>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </aside>
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