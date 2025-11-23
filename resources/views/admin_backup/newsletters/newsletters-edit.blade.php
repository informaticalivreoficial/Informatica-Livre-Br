@extends('adminlte::page')

@section('title', 'Editar Inscrito')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1><i class="fas fa-envelope mr-2"></i>Editar Inscrito</h1>
    </div>
    {{--dd($email->newsletterCat->titulo)--}}
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item"><a href="{{route('lista.newsletters', ['categoria' => $email->newsletterCat->id])}}">{{$email->newsletterCat->titulo}}</a></li>
            <li class="breadcrumb-item active">Editar Inscrito</li>
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
            
<form action="{{ route('listas.newsletter.update', [ 'id' => $email->id ]) }}" method="post" autocomplete="off">
@csrf    
@method('PUT')       
<div class="row">            
    <div class="col-12">
        <div class="card card-teal card-outline"> 
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                       <div class="row">
                            <div class="col-12 col-sm-6 col-md-4 col-lg-4">   
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>*Nome</b></label>
                                    <input type="text" class="form-control" name="nome" value="{{ old('nome') ?? $email->nome }}">
                                </div>                                                    
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-4">   
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>*Email</b></label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') ?? $email->email }}">
                                </div>                                                    
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Autorizado?</b></label>
                                    <select name="autorizacao" class="form-control">
                                        <option value="1" {{ (old('autorizacao') == '1' ? 'selected' : ($email->autorizacao == 1 ? 'selected' : '')) }}>Sim</option>
                                        <option value="0" {{ (old('autorizacao') == '0' ? 'selected' : ($email->autorizacao == 0 ? 'selected' : '')) }}>Não</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Selecione a Lista</b> <a style="font-size:11px;" href="{{route('listas.create')}}">(Cadastrar Lista)</a></label>
                                    <select name="categoria" class="form-control">
                                        @if (!empty($listas) && $listas->count() > 0)
                                            @foreach ($listas as $lista)
                                                <option value="{{$lista->id}}" {{(old('categoria') == $lista->id ? 'selected' : ($email->categoria == $lista->id ? 'selected' : ''))}}>{{$lista->titulo}}</option>
                                            @endforeach
                                        @else   
                                            <option value="">Cadastre uma Lista</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-4">   
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>WhatsApp</b></label>
                                    <input type="text" class="form-control whatsappmask" name="whatsapp" value="{{ old('whatsapp') ?? $email->whatsapp }}">
                                </div>                                                    
                            </div>
                            <div class="col-12 col-sm-4 col-md-4 col-lg-4"> 
                                <div class="form-group">
                                    <label class="labelforms" style="color:#fff;"><b>Atualizar</b></label>
                                    <button type="submit" class="btn btn-block btn-lg btn-success" title="Atualizar"><i class="nav-icon fas fa-check mr-2"></i> Atualizar</button>
                                </div>
                            </div>
                       </div>                        
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>                   
</form> 
@stop

@section('footer')
    <strong>Copyright &copy; {{env('DESENVOLVEDOR_INICIO')}} <a href="{{env('DESENVOLVEDOR_URL')}}">{{env('DESENVOLVEDOR')}}</a>.</strong> Desenvolvido por <a href="https://informaticalivre.com.br">Informática Livre</a>.
@endsection

@section('js')
<script src="{{url(asset('backend/assets/js/jquery.mask.js'))}}"></script>
<script>
    $(document).ready(function () { 
        var $whatsapp = $(".whatsappmask");
        $whatsapp.mask('(99) 99999-9999', {reverse: false});        
    });
</script>
@stop