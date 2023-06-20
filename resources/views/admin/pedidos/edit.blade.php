@extends('adminlte::page')

@section('title', 'Editar Pedido')

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
                    
            
<form action="{{ route('pedidos.update', $pedido->id) }}" method="post" autocomplete="off">
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
                <div class="tab-content" id="custom-tabs-four-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                        <div class="row mb-2 mt-2"> 
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
                            <div class="col-12 col-md-6 col-lg-4"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>*Orçamento:</b> <a style="font-size:11px;" href="{{route('vendas.orcamentos')}}">(Cadastrar Orçamento)</a></label>
                                    <select name="orcamento" class="form-control categoria">
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
                                                <option value="{{ $gateway->id }}" {{ (old('gateway') == $gateway->id ? 'selected' : ($gateway->id == $pedido->gateway ? 'selected' : '')) }}>{{ $gateway->nome }}</option> 
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
                                <div class="12">
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
                                                <td></td>                        
                                                <td class="text-center"></td> 
                                                <td class="text-center"></td> 
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
                                <div class="col-sm-12 text-right">                                                        
                                    <button type="button" class="btn btn-xs btn-danger text-white j_modal_insert" data-id="{{$pedido->id}}" data-toggle="modal" data-target="#modal-default">
                                        Cadastrar item para o pedido
                                    </button>                                                        
                                </div>                                
                            @endif                        
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
                <input id="id_pedido" name="pedido_id" type="hidden" value="{{$pedido->id}}"/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-2" id="js-result"></div>
                        <div class="col-7 col-md-7 col-lg-7 mb-2">
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
                        <div class="col-3 col-md-3 col-lg-3 mb-2">
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
        
        $('.j_modal_item_insert').click(function() {
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
                    form.find('.alert').fadeOut(500, function(){
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
                }
            });
        });
        
    });
</script>

@stop