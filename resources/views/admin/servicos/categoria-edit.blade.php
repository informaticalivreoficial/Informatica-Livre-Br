@extends('adminlte::page')

@php
//Variáveis
if($categoria->id_pai != null){
    $h1 = 'Editar Sub Categoria';
}else{
    $h1 = 'Editar Categoria';
}
@endphp

@section('title', "$h1")

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1><i class="fas fa-search mr-2"></i> {{$h1}}</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item"><a href="{{route('servicos-categorias.index')}}">Categorias</a></li>
            <li class="breadcrumb-item active">{{$h1}}</li>
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
            <div class="col-12">
                <div class="card card-teal card-outline">
                    <div class="card-body">
                        <form action="{{ route('servicos-categorias.update', [ 'id' => $categoria->id ]) }}" method="post" autocomplete="off">
                        @csrf
                        @method('PUT')
                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="labelforms"><b>Título da Categoria:</b></label>
                                    <input class="form-control" name="titulo" placeholder="Título da Categoria" value="{{old('titulo') ?? $categoria->titulo}}">
                                </div>
                            </div>
                            @if(!empty($catpai) && $categoria->id_pai != null)
                                <input type="hidden" name="tipo" value="{{$catpai->tipo}}"/>                                 
                            @endif                            
                            <div class="col-3">
                                <div class="form-group">
                                    <label class="labelforms"><b>Exibir no site?</b></label>
                                    <select class="form-control" name="status">
                                        <option value="1" {{(old('status') == '1' ? 'selected' : ($categoria->status == '1' ? 'selected' : ''))}}>Sim</option>
                                        <option value="0" {{(old('status') == '0' ? 'selected' : ($categoria->status == '0' ? 'selected' : ''))}}>Não</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label class="labelforms">&nbsp;</label>
                                    <button type="submit" style="width: 100%;" class="btn btn-success"><i class="nav-icon fas fa-check mr-2"></i> Atualizar Agora</button>
                                </div>
                            </div>                            
                        </div> 
                        </form>
                    </div>                
                </div>
            </div>
        </div>

@endsection