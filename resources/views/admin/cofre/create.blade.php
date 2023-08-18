@extends('adminlte::page')

@section('title', 'Cadastrar Item')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1><i class="fas fa-search mr-2"></i>Novo Item</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Painel de Controle</a></li>
            <li class="breadcrumb-item"><a href="{{route('cofre.index')}}">Items</a></li>
            <li class="breadcrumb-item active">Novo Item</li>
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
        
<form action="{{route('cofre.store')}}" method="post" enctype="multipart/form-data">
@csrf
<div class="row">            
    <div class="col-12">                            
        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-12 col-md-4 col-lg-3"> 
                        <div class="form-group">
                            <div class="thumb_user_admin">
                                @php
                                    if(!empty($item->logomarca) && \Illuminate\Support\Facades\File::exists(public_path() . '/storage/' . $item->logomarca)){
                                        $cover = url('storage/'.$item->logomarca);
                                    } else {
                                        $cover = url(asset('backend/assets/images/image.jpg'));
                                    }
                                @endphp
                                <img id="preview" src="{{$cover}}" alt="{{ old('name') }}" title="{{ old('name') }}"/>
                                <input id="img-input" type="file" name="logomarca">
                            </div>                                                
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-9">
                        <div class="row mb-2"> 
                            <div class="col-12 col-md-6 col-lg-6"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Nome:</b></label>
                                    <input type="text" class="form-control" placeholder="Nome" name="name" value="{{ old('name') }}">
                                </div>
                            </div>                                   
                            <div class="col-12 col-md-6 col-lg-6"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Email:</b></label>
                                    <input type="text" class="form-control" placeholder="Email" name="email" value="{{old('email')}}">
                                </div>
                            </div>                                    
                            <div class="col-12 col-md-6 col-lg-6"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Login:</b></label>
                                    <input type="text" class="form-control" placeholder="Login" name="login" value="{{ old('login') }}"/>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Token:</b></label>
                                    <input type="text" class="form-control" placeholder="Token" name="token" value="{{ old('token') }}"/>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Password:</b></label>
                                    <input type="text" class="form-control" placeholder="Password" name="password" value="{{ old('password') }}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="row mb-2">
                    <div class="col-12">   
                        <label class="labelforms text-muted"><b>Informações Adicionais</b></label>
                        <textarea class="form-control" rows="5" name="content">{{ old('content') }}</textarea>                                                      
                    </div>                                
                </div>
                <div class="row text-right">
                    <div class="col-12 mb-4 mt-4">
                        <button type="submit" class="btn btn-success"><i class="nav-icon fas fa-check mr-2"></i> Cadastrar Agora</button>
                    </div>
                </div>
            </div>
        </div>
               
    </div>
</div>
                        
</form>

@endsection

@section('css')    
    <style>
        /* Foto User Admin */
        .thumb_user_admin{
        border: 1px solid #ddd;
        border-radius: 4px; 
        text-align: center;
        }
        .thumb_user_admin input[type=file]{
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }
        .thumb_user_admin img{
            width: 100%;            
        }
    </style>
@stop

@section('js')

<script>
    $(function () { 

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function readImage() {
            if (this.files && this.files[0]) {
                var file = new FileReader();
                file.onload = function(e) {
                    document.getElementById("preview").src = e.target.result;
                };       
                file.readAsDataURL(this.files[0]);
            }
        }
        document.getElementById("img-input").addEventListener("change", readImage, false);

    });
</script>

@endsection