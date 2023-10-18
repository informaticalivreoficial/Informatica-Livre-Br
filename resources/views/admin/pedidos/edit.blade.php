@extends('adminlte::page')

@section('title', 'Editar Pedido')

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
        <h1>Editar Pedido</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item"><a href="{{route('pedidos.index')}}">Pedidos</a></li>
            <li class="breadcrumb-item active">Editar Pedido</li>
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
                    
            
<form id="frm" action="{{ route('pedidos.update', $pedido->id) }}" method="post" autocomplete="off">
@csrf
@method('PUT')          
<div class="row">            
    <div class="col-12"> 
        <div class="card card-teal card-outline card-outline-tabs"> 
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Informações</a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-itens-tab" data-toggle="pill" href="#custom-tabs-four-itens" role="tab" aria-controls="custom-tabs-four-itens" aria-selected="true">Itens do pedido</a>
                    </li> 
                </ul>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-12"> 
                        <div class="form-group">
                            <label class="labelforms text-muted"><b>Tipo de pedido</b></label>
                            <div class="form-check">
                                <input id="tipo-orcamento" class="form-check-input" type="radio" value="1" name="tipo_pedido" {{(old('tipo_pedido') == '1' ? 'checked' : ($pedido->tipo_pedido == 1 ? 'checked' : ''))}}>
                                <label for="tipo-orcamento" class="form-check-label mr-5">Orçamento</label>
                                <input id="tipo-produto" class="form-check-input" type="radio" value="0" name="tipo_pedido" {{(old('tipo_pedido') == '0' ? 'checked' : ($pedido->tipo_pedido == 0 ? 'checked' : '') )}}>
                                <label for="tipo-produto" class="form-check-label mr-5">Produto</label>
                                <input id="tipo-servico" class="form-check-input" type="radio" value="2" name="tipo_pedido" {{(old('tipo_pedido') == '2' ? 'checked' : ($pedido->tipo_pedido == 2 ? 'checked' : '') )}}>
                                <label for="tipo-servico" class="form-check-label">Serviço</label>
                            </div>
                        </div>
                    </div>                        
                </div>
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                        <div class="row mb-2 mt-2"> 
                            <div class="col-12 col-md-6 col-lg-4"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>*Serviços:</b> <a style="font-size:11px;" href="{{route('vendas.orcamentos')}}">(Cadastrar Serviço)</a></label>
                                    <select name="produto" class="form-control categoria tipo-servico">
                                        @if(!empty($produtos) && $produtos->count() > 0)
                                            <option value="">Selecione</option>
                                            @foreach($produtos as $produto) 
                                                <option value="{{ $produto->id }}" {{ (old('produto') == $produto->id ? 'selected' : ($produto->id == $pedido->produto ? 'selected' : '')) }}>{{ $produto->name }}</option>                                                                                                                      
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
                                                <option value="{{ $produto->id }}" {{ (old('produto') == $produto->id ? 'selected' : ($produto->id == $pedido->produto ? 'selected' : '')) }}>{{ $produto->name }} {{ ($produto->valor ? '- R$'.$produto->valor : '') }}</option>                                                                                                                      
                                            @endforeach
                                        @else
                                            <option value="">Cadastre um Produto</option>
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
                                                <option value="{{ $orcamento->id }}" {{ (old('orcamento') == $orcamento->id ? 'selected' : ($orcamento->id == $pedido->orcamento ? 'selected' : '')) }}>{{ $orcamento->created_at  }} - {{ $orcamento->name }}</option>                                                                                                                      
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
                                                <option value="{{ $empresa->id }}" {{ (old('empresa') == $empresa->id ? 'selected' : ($empresa->id == $pedido->empresa ? 'selected' : '')) }}>{{ $empresa->id }} - {{ $empresa->alias_name }}</option>                                                                                                                      
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
                                        <option value="canceled" {{ (old('status') == 'canceled' ? 'selected' : ($pedido->status == 'canceled' ? 'selected' : '')) }}>Cancelado</option>
                                        <option value="pending" {{ (old('status') == 'pending' ? 'selected' : ($pedido->status == 'pending' ? 'selected' : '')) }}>Pendente</option>
                                        <option value="reserved" {{ (old('status') == 'reserved' ? 'selected' : ($pedido->status == 'reserved' ? 'selected' : '')) }}>Reservado</option>
                                        <option value="completed" {{ (old('status') == 'completed' ? 'selected' : ($pedido->status == 'completed' ? 'selected' : '')) }}>Completo/Pago</option>
                                        <option value="paid" {{ (old('status') == 'paid' ? 'selected' : ($pedido->status == 'paid' ? 'selected' : '')) }}>Aprovado</option>
                                        <option value="processing" {{ (old('status') == 'processing' ? 'selected' : ($pedido->status == 'processing' ? 'selected' : '')) }}>Análise</option>
                                        <option value="refunded" {{ (old('status') == 'refunded' ? 'selected' : ($pedido->status == 'refunded' ? 'selected' : '')) }}>Estornado</option>
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
                                                <option value="{{ $gateway->id }}" {{ (old('gateway') == $gateway->id ? 'selected' : ($gateway->id == $pedido->gateway ? 'selected' : '')) }}>{{ $gateway->nome }}</option> 
                                            @endforeach
                                        @else
                                            <option value="">Cadastre Gateways</option>
                                        @endif                                                                       
                                    </select>
                                </div>
                            </div>  
                            @if ($pedido->tipo_pagamento == 0)
                                <div class="col-12 col-sm-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label class="labelforms text-muted"><b>Período:</b></label>
                                        <select name="periodo" class="form-control">
                                            <option value="1" {{ (old('periodo') == '1' ? 'selected' : ($pedido->periodo == 1 ? 'selected' : '')) }}>Mensal</option>
                                            <option value="3" {{ (old('periodo') == '3' ? 'selected' : ($pedido->periodo == 3 ? 'selected' : '')) }}>Trimestral</option>
                                            <option value="6" {{ (old('periodo') == '6' ? 'selected' : ($pedido->periodo == 6 ? 'selected' : '')) }}>Semestral</option>
                                            <option value="12" {{ (old('periodo') == '12' ? 'selected' : ($pedido->periodo == 12 ? 'selected' : '')) }}>Anual</option>
                                            <option value="24" {{ (old('periodo') == '24' ? 'selected' : ($pedido->periodo == 24 ? 'selected' : '')) }}>Bi-Anual</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-12 col-sm-5 col-md-5 col-lg-4"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>*Vencimento</b></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker-here" data-language='pt-BR' name="vencimento" value="{{ old('vencimento') ?? Carbon\Carbon::parse($pedido->vencimento)->format('d/m/Y') }}"/>
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
                                <x-adminlte-text-editor name="notas_adicionais" v placeholder="Informações Adicionais..." :config="$config">{{ old('notas_adicionais') ?? $pedido->notas_adicionais }}</x-adminlte-text-editor>                                                                                     
                            </div>                        
                        </div>                              
                    

                        <div class="row text-right">
                            <div class="col-12 my-3">
                                <button type="submit" class="btn btn-lg btn-success"><i class="nav-icon fas fa-check mr-2"></i> Atualizar Agora</button>
                            </div>
                        </div> 
                    </div>

                    <div class="tab-pane fade show" id="custom-tabs-four-itens" role="tabpanel" aria-labelledby="custom-tabs-four-itens-tab">
                        <div class="row mb-2 mt-3">
                            @if(!empty($pedido->itens()->get()) && $pedido->itens()->count() > 0)
                                <div class="col-12">
                                    <table class="table table-bordered table-striped projects">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Descrição</th>                                                
                                                <th class="text-center">Quantidade</th>                        
                                                <th class="text-center">Valor</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pedido->itens()->get() as $item)                    
                                            <tr>
                                                <td>{{$item->descricao}}</td>                        
                                                <td class="text-center">{{$item->quantidade}}</td> 
                                                <td class="text-center">R$ {{str_replace(',00', '', $item->valor)}}</td> 
                                                <td> 
                                                    <a href="" class="btn btn-xs btn-default"><i class="fas fa-pen"></i></a>                        
                                                    <button type="button" class="btn btn-xs btn-danger text-white j_modal_btn" data-id="{{$item->id}}" data-toggle="modal" data-target="#modal-default">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>                
                                    </table>
                                </div> 
                            @else                                
                                <div class="col-12">                                                        
                                    <div class="alert alert-info p-3">
                                        Não foram encontrados registros!
                                    </div>                                                        
                                </div>                         
                            @endif 
                            <div class="col-sm-12 text-right">                                                        
                                <button type="button" class="btn btn-xs btn-success text-white j_modal_insert" data-id="{{$pedido->id}}" data-toggle="modal" data-target="#modal-default">
                                    Cadastrar item para o pedido
                                </button>                                                        
                            </div>                       
                        </div>
                    </div>

                </div>
            </div> 
        </div>
    </div>
</div>                       
</form>  

<div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="" method="post" class="j_modal_item_insert">            
            @csrf            
                <input name="pedido" type="hidden" value="{{$pedido->id}}"/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-2" id="js-result"></div>
                        <div class="col-7 col-md-8 col-lg-8 mb-2">
                            <div class="form-group">
                                <label class="labelforms text-muted"><b>*Descrição</b></label>
                                <input type="text" class="form-control" name="descricao" value="">
                            </div>
                        </div>
                        <div class="col-2 col-md-2 col-lg-2 mb-2">
                            <div class="form-group">
                                <label class="labelforms text-muted"><b>*Quantidade</b></label>
                                <input type="text" class="form-control" name="quantidade" value="">
                            </div>
                        </div>
                        <div class="col-3 col-md-2 col-lg-2 mb-2">
                            <div class="form-group">
                                <label class="labelforms text-muted"><b>*Valor</b></label>
                                <input type="text" class="form-control mask-money" name="valor" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-item">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
</div>
            
@stop

@section('css')
<style>
    .invalid-feedback {
        display: block;
    }
</style>
<link href="{{url(asset('backend/plugins/airdatepicker/css/datepicker.min.css'))}}" rel="stylesheet" type="text/css">
@endsection

@section('js')
<script src="{{url(asset('backend/plugins/airdatepicker/js/datepicker.min.js'))}}"></script>
<script src="{{url(asset('backend/plugins/airdatepicker/js/i18n/datepicker.pt-BR.js'))}}"></script>
<script src="{{url(asset('backend/assets/js/jquery.mask.js'))}}"></script>
<script>
    $(document).ready(function () { 
       var $money = $(".mask-money");
        $money.mask('R$ 000.000.000.000.000,00', {reverse: true, placeholder: "R$ 0,00"});
    });
</script>

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
                $('#frm').prop('action', "{{ route('pedidos.update', $pedido->id) }}");
            }else if($('#tipo-produto').prop('checked')){
                $('.tipo-orcamento, .tipo-servico').prop('disabled', true);
                $('.tipo-produto').prop('disabled', false);
                $('#frm').prop('action', "{{ route('pedidos.updateProduct', $pedido->id) }}");
            }else if($('#tipo-servico').prop('checked')){
                $('.tipo-produto, .tipo-orcamento').prop('disabled', true);
                $('.tipo-servico').prop('disabled', false);
                $('#frm').prop('action', "{{ route('pedidos.updateService', $pedido->id) }}");
            }else{
                $('.tipo-servico, .tipo-produto, tipo-orcamento').prop('disabled', true);
            }
        } 

        tipoPedido();

        $('#tipo-orcamento').change( function(){
            if(this.checked){
                $('.tipo-servico, .tipo-produto').prop('disabled', true);
                $('.tipo-orcamento').prop('disabled', false);
                $('#frm').prop('action', "{{ route('pedidos.update', $pedido->id) }}");
            }
        });
        $('#tipo-produto').change( function(){
            if(this.checked){
                $('.tipo-orcamento, .tipo-servico').prop('disabled', true);
                $('.tipo-produto').prop('disabled', false);
                $('#frm').prop('action', "{{ route('pedidos.updateProduct', $pedido->id) }}");
            }
        });
        $('#tipo-servico').change( function(){
            if(this.checked){
                $('.tipo-produto, .tipo-orcamento').prop('disabled', true);
                $('.tipo-servico').prop('disabled', false);
                $('#frm').prop('action', "{{ route('pedidos.updateService', $pedido->id) }}");
            }
        });
        
        $('.j_modal_item_insert').submit(function() {
            var form = $(this);
            var dataString = $(form).serialize();
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: "{{ route('storeItem.store') }}",
                data: dataString,
                beforeSend: function(){
                    form.find(".btn-item").attr("disabled", true);
                    form.find('.btn-item').html("Carregando...");                
                    form.find('.invalid-feedback').fadeOut(500, function(){
                        $(this).remove();
                    });
                },
                success:function(data) {
                    if(data.error){
                        form.find('#js-result').html('<div class="alert alert-danger">'+ data.error +'</div>');
                        form.find('.error-msg').fadeIn();                    
                    }else{
                        form.find('#js-result').html('<div class="alert alert-success">'+ data.success +'</div>');
                        form.find('.error-msg').fadeIn();                    
                        form.find('input[class!="noclear"]').val('');
                        //form.find('.form_hide').fadeOut(500);
                    }
                },
                complete: function(data){
                    form.find(".btn-item").attr("disabled", false);
                    form.find('.btn-item').html("Cadastrar");                                
                },
                error:function (data){
                    $.each(data.responseJSON.errors,function(field_name,error){
                        form.find('[name='+field_name+']').after('<span class="error invalid-feedback">' +error+ '</span>')
                    })
                }
            });

            return false;
        });
        
    });
</script>


@stop