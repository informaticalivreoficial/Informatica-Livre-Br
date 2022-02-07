@extends('web.master.master')

@section('content')
<section class="section section-30 section-xxl-40 section-xxl-66 section-xxl-bottom-90 novi-background bg-gray-dark page-title-wrap" style="background-image: url({{$configuracoes->gettopodosite()}});">
    <div class="container">
        <div class="page-title">
            <h2>{{$projeto->name}}</h2>
        </div>
    </div>
</section>

<section class="section section-66 section-md-90 section-xl-bottom-120 novi-background">
    <div class="container">
        <div class="row row-40 justify-content-lg-between">
            <div class="col-12 col-md-6 col-lg-7 text-secondary">
                <div class="inset-md-right-15 inset-xl-right-0">
                   <img src="{{$projeto->nocover()}}" alt="">                    
                </div>
            </div>            
            <div class="col-12 col-md-6 col-lg-5 text-secondary">
                <div class="inset-md-right-15 inset-xl-right-0">                    
                    <h3>Detalhes do Projeto</h3>
                    <ul class="list-marked-bordered">
                        <li>
                            <a target="_blank" style="padding: 3px 7px;" href="{{$projeto->link}}">
                                <span>Domínio:</span>
                                <span class="list-counter"> {{$projeto->link}}</span>
                            </a>
                        </li>
                        <li>
                            <a style="padding: 3px 7px;" href="mailto:{{$projeto->empresaObject->email}}">
                                <span>Email:</span>
                                <span class="list-counter"> {{$projeto->empresaObject->email}}</span>
                            </a>
                        </li>                        
                    </ul>
                    <p>
                        {!!$projeto->content!!} 
                    </p>
                </div>
            </div>            
        </div>
    </div>
</section>

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