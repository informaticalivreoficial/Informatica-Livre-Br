@extends('adminlte::page')

@section('title', 'Escrever Mensagem')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1><i class="fas fa-search mr-2"></i>Escrever Mensagem</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item active">Escrever Mensagem</li>
        </ol>
    </div>
</div> 
@stop


@section('content') 
<div class="row">
    <div class="col-12">
        @if($errors->all())
            @foreach($errors->all() as $error)
                @message(['color' => 'danger'])
                {{ $error }}
                @endmessage
            @endforeach
        @endif 

        @if(session()->exists('message'))
            @message(['color' => session()->get('color')])
            {{ session()->get('message') }}
            @endmessage
        @endif
    </div>            
</div>
<div class="row">                
    <div class="col-12 text-center">
        <div class="card card-teal card-outline p-4">
            <div class="card-body">
                <a href="{{ route('email.send', Auth::user()->id ) }}" class="btn btn-lg btn-primary">Enviar um novo email</a>
            </div>                        
        </div>
    </div>                
</div>
@endsection