



<div style="position: absolute;
            top: 15%; 
            left: 35%;
            margin-left: -100px;
            margin-top: -25px;
            text-align:center; ">
    <h1>Laravel teste usando Biblioteca Simple Qrcode</h1>
    
   @php
       $qrcode = 'data:image/png;base64,'.base64_encode(QrCode::format('png')
        ->merge('https://logospng.org/download/laravel/logo-laravel-icon-1024.png', .22, true)
        ->errorCorrection('H')
        ->size(300)
        ->generate(url('http://etus.com.br')));
   @endphp

   <img src="{{$qrcode}}">
    
    <p>Me escaneie!</p>
</div>