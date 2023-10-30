@extends('adminlte::page')

@section('title', 'Cadastrar Pedido')

@php
$config = [
    "height" => "300",
    "fontSizes" => ['8', '9', '10', '11', '12', '14', '18'],
    "lang" => 'pt-BR',
    "toolbar" => [
        // [groupName, [list of button]]
        ['style', ['style']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['style', ['bold', 'italic', 'underline', 'clear']],
        //['font', ['strikethrough', 'superscript', 'subscript']],        
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video','hr']],
        ['view', ['fullscreen', 'codeview']],
    ],
]
@endphp

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
                    
            
<form id="frm" action="{{ route('pedidos.store') }}" method="post" autocomplete="off">
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
                    <div class="row mb-2">
                        <div class="col-12"> 
                            <div class="form-group">
                                <label class="labelforms text-muted"><b>Tipo de pedido</b></label>
                                <div class="form-check">
                                    <input id="tipo-orcamento" class="form-check-input" type="radio" value="1" name="tipo_pedido" {{(old('tipo_pedido') == '1' ? 'checked' : '')}}>
                                    <label for="tipo-orcamento" class="form-check-label mr-5">Orçamento</label>
                                    <input id="tipo-produto" class="form-check-input" type="radio" value="0" name="tipo_pedido" {{(old('tipo_pedido') == '0' ? 'checked' : '' )}}>
                                    <label for="tipo-produto" class="form-check-label mr-5">Produto</label>
                                    <input id="tipo-servico" class="form-check-input" type="radio" value="2" name="tipo_pedido" {{(old('tipo_pedido') == '2' ? 'checked' : '' )}}>
                                    <label for="tipo-servico" class="form-check-label">Serviço</label>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class="row mb-2 mt-2"> 
                        <div class="col-12 col-md-6 col-lg-4"> 
                            <div class="form-group">
                                <label class="labelforms text-muted"><b>*Serviços:</b> <a style="font-size:11px;" href="{{route('vendas.orcamentos')}}">(Cadastrar Serviço)</a></label>
                                <select name="servico" class="form-control categoria tipo-servico">
                                    @if(!empty($servicos) && $servicos->count() > 0)
                                        <option value="">Selecione</option>
                                        @foreach($servicos as $servico) 
                                            <option value="{{ $servico->id }}" {{ (old('servico') == $servico->id ? 'selected' : '') }}>{{ $servico->name }}</option>                                                                                                                      
                                        @endforeach
                                    @else
                                        <option value="">Cadastre um Orçamento</option>
                                    @endif                                                                                
                                </select>
                            </div>
                        </div> 
                        <div class="col-12 col-md-6 col-lg-4"> 
                            <div class="form-group">
                                <label class="labelforms text-muted"><b>*Produtos:</b> <a style="font-size:11px;" href="{{route('vendas.orcamentos')}}">(Cadastrar Produto)</a></label>
                                <select name="produto" class="form-control categoria tipo-produto">
                                    @if(!empty($produtos) && $produtos->count() > 0)
                                        <option value="">Selecione</option>
                                        @foreach($produtos as $produto) 
                                            <option value="{{ $produto->id }}" {{ (old('produto') == $produto->id ? 'selected' : '') }}>{{ $produto->name }} {{ ($produto->valor ? '- R$'.$produto->valor : '') }}</option>                                                                                                                      
                                        @endforeach
                                    @else
                                        <option value="">Cadastre um Orçamento</option>
                                    @endif                                                                                
                                </select>
                            </div>
                        </div> 
                        <div class="col-12 col-md-6 col-lg-4"> 
                            <div class="form-group">
                                <label class="labelforms text-muted"><b>*Orçamento:</b> <a style="font-size:11px;" href="{{route('vendas.orcamentos')}}">(Cadastrar Orçamento)</a></label>
                                <select name="orcamento" class="form-control categoria tipo-orcamento">
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
                        
                        <div class="col-12 col-sm-4 col-md-4 col-lg-4">
                            <div class="form-group">
                                <label class="labelforms text-muted"><b>Status:</b></label>
                                <select name="status" class="form-control">
                                    <option value="canceled" {{ (old('status') == 'canceled' ? 'selected' : '') }}>Cancelado</option>
                                    <option value="pending" {{ (old('status') == 'pending' ? 'selected' : '') }}>Pendente</option>
                                    <option value="reserved" {{ (old('status') == 'reserved' ? 'selected' : '') }}>Reservado</option>
                                    <option value="completed" {{ (old('status') == 'completed' ? 'selected' : '') }}>Completo/Pago</option>
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
                            <label class="labelforms text-muted"><b>Informações Adicionais</b></label>
                            <x-adminlte-text-editor name="notas_adicionais" v placeholder="Informações Adicionais..." :config="$config">{{ old('notas_adicionais') }}</x-adminlte-text-editor>                                                                                     
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
        
        function tipoPedido() {
            if ($('#tipo-orcamento').prop('checked')) {
                $('.tipo-servico, .tipo-produto').prop('disabled', true);
                $('.tipo-orcamento').prop('disabled', false);
                $('#frm').prop('action', "{{ route('pedidos.store') }}");
            }else if($('#tipo-produto').prop('checked')){
                $('.tipo-orcamento, .tipo-servico').prop('disabled', true);
                $('.tipo-produto').prop('disabled', false);
                $('#frm').prop('action', "{{ route('pedidos.storeProduct') }}");
            }else if($('#tipo-servico').prop('checked')){
                $('.tipo-produto, .tipo-orcamento').prop('disabled', true);
                $('.tipo-servico').prop('disabled', false);
                $('#frm').prop('action', "{{ route('pedidos.storeService') }}");
            }else{
                $('.tipo-servico, .tipo-produto, tipo-orcamento').prop('disabled', true);
            }
        } 

        tipoPedido();

        $('#tipo-orcamento').change( function(){
            if(this.checked){
                $('.tipo-servico, .tipo-produto').prop('disabled', true);
                $('.tipo-orcamento').prop('disabled', false);
                $('#frm').prop('action', "{{ route('pedidos.store') }}");
            }
        });
        $('#tipo-produto').change( function(){
            if(this.checked){
                $('.tipo-orcamento, .tipo-servico').prop('disabled', true);
                $('.tipo-produto').prop('disabled', false);
                $('#frm').prop('action', "{{ route('pedidos.storeProduct') }}");
            }
        });
        $('#tipo-servico').change( function(){
            if(this.checked){
                $('.tipo-produto, .tipo-orcamento').prop('disabled', true);
                $('.tipo-servico').prop('disabled', false);
                $('#frm').prop('action', "{{ route('pedidos.storeService') }}");
            }
        });
        
    });
</script>
@stop