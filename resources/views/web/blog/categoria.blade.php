@extends('web.master.master')

@section('content')
<section class="section section-30 section-xxl-40 section-xxl-66 section-xxl-bottom-90 novi-background bg-gray-dark page-title-wrap" style="background-image: url({{$configuracoes->gettopodosite()}});">
    <div class="container">
        <div class="page-title">
            <h2>Blog - {{$categoria->titulo}}</h2>
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
            @if($posts->hasPages())                  
                {{ $posts->links() }}                
            @endif 
        </div>
    </div>
</section>
@endif

<section class="section section-60 section-md-100 bg-accent novi-background">
    <div class="container text-center text-lg-start">
        <div class="row row-30 align-items-md-center justify-content-lg-center">
            <div class="col-lg-8 col-xl-7">
                <h3>Solicite Agora um Orçamento</h3>
            </div>
            <div class="col-lg-4 col-xl-3">
                <a class="btn btn-xl btn-black-outline" href="{{route('web.formorcamento')}}">Quero um Orçamento</a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('css')

@endsection

@section('js')

@endsection