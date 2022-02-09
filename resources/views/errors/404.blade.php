@extends('errors::minimal')

@section('title', __('Página não encontrada'))

@section('content-error')
    <section class="section section-single novi-background bg-gray-darker novi-background" style="background-image: url(images/bg-404.jpg);">
        <div class="section-single-inner">      
            <div class="section-single-main">
                <div class="container">
                    <div class="row justify-content-md-center">
                        <div class="col-md-9 col-lg-8">
                            <h5>Desculpe, página não encontrada</h5>
                            <div class="text-extra-large-bordered">
                                <p>404</p>
                            </div> 
                            <div class="group-xl">
                                <a class="btn btn-primary" href="{{route('web.home')}}">Voltar para Home</a> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>      
        </div>
    </section>
@endsection
