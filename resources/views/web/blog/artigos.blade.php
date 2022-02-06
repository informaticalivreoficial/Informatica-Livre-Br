@extends('web.master.master')

@section('content')
<section class="section section-30 section-xxl-40 section-xxl-66 section-xxl-bottom-90 novi-background bg-gray-dark page-title-wrap" style="background-image: url({{$configuracoes->gettopodosite()}});">
    <div class="container">
        <div class="page-title">
            <h2>Blog</h2>
        </div>
    </div>
</section>

@if($posts->count() && $posts->count() > 0)
<section class="section section-50 section-md-75 section-xl-100">
    <div class="container">
        <div class="row row-30 justify-content-md-center justify-content-lg-start">
            @foreach($posts as $artigo)
                <div class="col-md-9 col-lg-6 height-fill">
                    <article class="post-block">
                        <div class="post-image">
                            <img src="{{$artigo->cover()}}" alt="" width="570" height="253" />
                        </div>
                        <div class="post-body">
                            <h4 class="post-header">
                                <a href="{{route('web.blog.artigo',['slug' => $artigo->slug])}}">{{$artigo->titulo}}</a>
                            </h4>
                            <ul class="post-meta">
                                <li class="object-inline">
                                    <span class="novi-icon icon icon-xxs icon-white material-icons-query_builder"></span>
                                    <time datetime="2021-01-01">há 1 mês</time>
                                </li>
                                <li class="object-inline">
                                    <span class="novi-icon icon icon-xxs icon-white material-icons-loyalty"></span>
                                    <ul class="list-tags-inline">
                                        <li><a href="{{route('web.blog.categoria', ['slug' => $artigo->categoriaObject->slug] )}}">{{$artigo->categoriaObject->titulo}}</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
        <div class="pagination-custom-wrap text-center">
            @if (isset($filters))
                {{ $posts->appends($filters)->links() }}
            @else
                {{ $posts->links() }}
            @endif 
        </div>
    </div>
</section>
@endif















      
<main class="page-content">
<!-- Blog Classic-->
<div id="fb-root"></div>
<section class="section-90 section-md-111 text-left bg-zircon">
    <div class="shell">
    <div class="range range-xs-center range-lg-right">
        
        <div class="cell-sm-10 cell-md-4 offset-top-60 offset-md-top-0">
        <div class="inset-md-left-30">
            <!-- Aside-->
            <aside class="text-left">
           
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