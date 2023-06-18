@extends('adminlte::page')

@section('title', 'Cadastrar Pedido')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Cadastrar novo Pedido</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item"><a href="{{route('pedidos.index')}}">Pedidos</a></li>
            <li class="breadcrumb-item active">Cadastrar novo Pedido</li>
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
    </div>            
</div>   
                    
            
<form action="{{ route('pedidos.store') }}" method="post" autocomplete="off">
@csrf          
<div class="row">            
    <div class="col-12"> 
        <div class="card card-teal card-outline card-outline-tabs"> 
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Informações</a>
                    </li> 
                </ul>
            </div>
            <div class="card-body">
                    <div class="row mb-2 mt-2"> 
                        <div class="col-12 col-md-6 col-lg-4"> 
                            <div class="form-group">
                                <label class="labelforms text-muted"><b>*Empresa:</b> <a style="font-size:11px;" href="{{route('empresas.index')}}">(Cadastrar Empresa)</a></label>
                                <select name="empresa" class="form-control categoria">
                                    @if(!empty($empresas) && $empresas->count() > 0)
                                        <option value="">Selecione a Empresa</option>
                                        @foreach($empresas as $empresa) 
                                            <option value="{{ $empresa->id }}" {{ (old('empresa') == $empresa->id ? 'selected' : '') }}>{{ $empresa->id }} - {{ $empresa->alias_name }}</option>                                                                                                                      
                                        @endforeach
                                    @else
                                        <option value="">Cadastre uma Empresa</option>
                                    @endif
                                                                                
                                </select>
                            </div>
                        </div> 
                        <div class="col-12 col-md-6 col-lg-4"> 
                            <div class="form-group">
                                <label class="labelforms text-muted"><b>*Orçamento:</b> <a style="font-size:11px;" href="{{route('vendas.orcamentos')}}">(Cadastrar Orçamento)</a></label>
                                <select name="orcamento" class="form-control categoria">
                                    @if(!empty($orcamentos) && $orcamentos->count() > 0)
                                        <option value="">Selecione</option>
                                        @foreach($orcamentos as $orcamento) 
                                            <option value="{{ $orcamento->id }}" {{ (old('orcamento') == $orcamento->id ? 'selected' : '') }}>{{ $orcamento->created_at  }} - {{ $orcamento->name }}</option>                                                                                                                      
                                        @endforeach
                                    @else
                                        <option value="">Cadastre um Orçamento</option>
                                    @endif
                                                                                
                                </select>
                            </div>
                        </div> 
                        <div class="col-12 col-sm-4 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label class="labelforms text-muted"><b>Status:</b></label>
                                <select name="status" class="form-control">
                                    <option value="canceled" {{ (old('status') == 'canceled' ? 'selected' : '') }}>Cancelado</option>
                                    <option value="pending" {{ (old('status') == 'pending' ? 'selected' : '') }}>Pendente</option>
                                    <option value="reserved" {{ (old('status') == 'reserved' ? 'selected' : '') }}>Reservado</option>
                                    <option value="completed" {{ (old('status') == 'completed' ? 'selected' : '') }}>Completo</option>
                                    <option value="paid" {{ (old('status') == 'paid' ? 'selected' : '') }}>Aprovado</option>
                                    <option value="processing" {{ (old('status') == 'processing' ? 'selected' : '') }}>Análise</option>
                                    <option value="refunded" {{ (old('status') == 'refunded' ? 'selected' : '') }}>Estornado</option>
                                </select>
                            </div>
                        </div> 
                        <div class="col-12 col-sm-5 col-md-5 col-lg-4">
                            <div class="form-group">
                                <label class="labelforms text-muted"><b>*Gateway:</b></label>
                                <select name="gateway" class="form-control">
                                    @if(!empty($gateways) && $gateways->count() > 0)
                                        <option value="">Selecione</option>
                                        @foreach($gateways as $gateway)        
                                            <option value="{{ $gateway->id }}" {{ (old('gateway') == $gateway->id ? 'selected' : '') }}>{{ $gateway->nome }}</option> 
                                        @endforeach
                                    @else
                                        <option value="">Cadastre Gateways</option>
                                    @endif                                                                       
                                </select>
                            </div>
                        </div>  
                        <div class="col-12 col-sm-5 col-md-5 col-lg-4"> 
                            <div class="form-group">
                                <label class="labelforms text-muted"><b>*Vencimento</b></label>
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker-here" data-language='pt-BR' name="vencimento" value="{{ old('vencimento') }}"/>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>                                                    
                        </div>                                   
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-12"> 
                            
                        </div>                        
                    </div>                              
                

                <div class="row text-right">
                    <div class="col-12 my-3">
                        <button type="submit" class="btn btn-lg btn-success"><i class="nav-icon fas fa-check mr-2"></i> Cadastrar Agora</button>
                    </div>
                </div> 
            </div> 
        </div>
    </div>
</div>                       
</form>                 
            
@stop

@section('css')
<link href="{{url(asset('backend/plugins/airdatepicker/css/datepicker.min.css'))}}" rel="stylesheet" type="text/css">
@endsection

@section('js')
<script src="{{url(asset('backend/plugins/airdatepicker/js/datepicker.min.js'))}}"></script>
<script src="{{url(asset('backend/plugins/airdatepicker/js/i18n/datepicker.pt-BR.js'))}}"></script>


<script type="application/javascript">
    $(function () { 
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });          
        
    });
</script>
@stop