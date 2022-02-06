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
@endsection