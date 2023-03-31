@extends('adminlte::page')

@section('title', 'Gerenciar Bancos')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1><i class="fas fa-search mr-2"></i> Bancos</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">                    
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item active">Bancos</li>
        </ol>
    </div>
</div>
@stop

@section('content')

<section class="content">
    <!-- Default box -->
      <div class="card card-solid">
        <div class="card-body pb-0">
            <div class="row">
                <div class="col-12">                
                    @if(session()->exists('message'))
                        @message(['color' => session()->get('color')])
                            {{ session()->get('message') }}
                        @endmessage
                    @endif
                </div>            
            </div>
            <div class="row">                
                <div class="col-12 my-2 text-right">
                    <a href="{{route('bancos.refresh')}}" class="btn btn-sm btn-default"><i class="fas fa-plus mr-2"></i> Atualizar</a>
                </div>
            </div>            
            @if(!empty($bancos) && $bancos->count() > 0)
                <div class="row d-flex align-items-stretch">
                @foreach($bancos as $banco)  
                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                    <div class="card bg-light">
                        <div class="card-header text-muted border-bottom-0">
                            {{$banco->bank_code}}
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-7">
                                    <h2 class="lead"><b>{{$banco->bank_name}}</b></h2>
                                    <p class="text-muted text-sm"><b>Conta:</b> {{$banco->account_type}}</p>
                                    <p class="text-muted text-sm"><b>Data de Entrada: </b><br> 
                                        {{Carbon\Carbon::parse($banco->created_at)->format('d/m/Y')}}</p>
                                    <ul class="ml-4 mb-0 fa-ul text-muted">
                                        <li class="small">
                                            <b>Agência:</b> {{$banco->agencia}} - <b>Conta:</b> {{$banco->conta}}
                                        </li>  
                                    </ul>
                                </div>
                                <div class="col-5 text-center">                            
                                <img src="{{$banco->getLogo()}}" alt="{{$banco->bank_name}}" class="img-circle img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-right"> 
                                <a href="" class="btn btn-xs btn-default"><i class="fas fa-pen"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach            
              </div>
            @else
                <div class="row mb-4">
                    <div class="col-12">                                                        
                        <div class="alert alert-info p-3">
                            Não foram encontrados registros!
                        </div>                                                        
                    </div>
                </div>
            @endif          
        </div>
        <!-- /.card-body -->
        <div class="card-footer paginacao">  
            {{ $bancos->links() }}
        </div>
          
      </div>
      <!-- /.card -->
</section>
@stop

@section('plugins.Toastr', true)

@section('css')
<link href="{{url(asset('backend/plugins/bootstrap-toggle/bootstrap-toggle.min.css'))}}" rel="stylesheet">
@stop

@section('js')    
    <script src="{{url(asset('backend/plugins/bootstrap-toggle/bootstrap-toggle.min.js'))}}"></script>
    <script>
       $(function () {           
           
           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }); 
            
        });
    </script>
@endsection