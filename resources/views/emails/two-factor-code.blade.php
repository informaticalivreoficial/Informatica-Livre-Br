@component('mail::layout')

    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    <div style="background:#FFF; font:16px Trebuchet MS, Arial, Helvetica, sans-serif; color:#333; line-height:150%;">
        <h1 style="font-size:20px; color:#000; background:#F4F4F4; padding:10px;">
            Olá <strong style="color:#09F;">{{ $name }}</strong>!
        </h1>

        <p>Recebemos uma tentativa de acesso ao painel administrativo com o e-mail <b>{{ $email }}</b>.</p>

        <p>Use o código abaixo para concluir a verificação em duas etapas:</p>

        <p style="text-align:center; margin:24px 0;">
            <span style="display:inline-block; font-size:30px; font-weight:bold; letter-spacing:8px; color:#000; background:#F4F4F4; padding:12px 24px; border-radius:6px;">
                {{ $code }}
            </span>
        </p>

        <p>O código expira em <b>{{ $expiresInMinutes }} minutos</b> e só pode ser usado uma vez.</p>

        <p style="color:#a00;">
            Se você não tentou acessar o painel, ignore este e-mail e altere sua senha imediatamente.
        </p>

        <p>
            att,<br>
            {{ config('app.name') }}
        </p>
    </div>
@endcomponent
