@extends('web.master.master')

@section('content')
<section class="section section-30 section-xxl-40 section-xxl-66 section-xxl-bottom-90 novi-background bg-gray-dark page-title-wrap" style="background-image: url({{$configuracoes->gettopodosite()}});">
    <div class="container">
        <div class="page-title">
            <h2>Portifólio</h2>
        </div>
    </div>
</section>

@if (!empty($projetos) && $projetos->count() > 0)
    <section class="section section-50 section-md-90 section-lg-bottom-120 section-xl-bottom-165">
        <div class="container isotope-wrap text-center">
            <div class="row row-40">
                <div class="col-sm-12">
                    <ul class="isotope-filters-responsive">
                        <li>
                            <p>Selecione uma Categoria:</p>
                        </li>
                        <li class="block-top-level">
                            <button class="isotope-filters-toggle btn btn-sm btn-default" data-custom-toggle="#isotope-1" data-custom-toggle-hide-on-blur="true" data-custom-toggle-disable-on-blur="true">Filter<span class="caret"></span></button>
                            <div class="isotope-filters isotope-filters-minimal isotope-filters-horizontal" id="isotope-1">
                                <ul class="list-inline">
                                    <li><a class="active" data-isotope-filter="*" href="#">Todos</a></li>
                                    @foreach ($catProjetos as $cat)
                                        <li><a data-isotope-filter="{{$cat->slug}}" href="#">{{$cat->titulo}}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-12">
                    <div class="row isotope isotope-gutter-default" data-lightgallery="group" data-lg-thumbnail="false">
                        @foreach ($projetos as $projeto)
                            <div class="col-12 col-md-6 col-lg-4 isotope-item" data-filter="{{$projeto->categoriaObject->slug}}">
                                <div class="thumbnail thumbnail-variant-3">
                                    <a class="link link-external" href="{{route('web.projeto',['slug' => $projeto->slug])}}">
                                        <span class="novi-icon icon icon-sm fa fa-link"></span>
                                    </a>
                                    <figure>
                                        <img class="imgportifolio" src="{{$projeto->cover()}}" alt="{{$projeto->name}}" />
                                    </figure>
                                    <div class="caption">
                                        <a class="link link-original" href="{{$projeto->nocover()}}" data-lightgallery="item"></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
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
    <style>
        .imgportifolio{
            width: 370px !important;
            height: 278px !important;
        }
    </style>
@endsection