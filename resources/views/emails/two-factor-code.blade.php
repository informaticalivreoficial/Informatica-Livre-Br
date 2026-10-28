@component('mail::message')

# Olá, {{ $name }}!

Recebemos uma tentativa de acesso ao painel administrativo com o e-mail **{{ $email }}**.

Use o código abaixo para concluir a verificação em duas etapas:

@component('mail::panel')
<div style="text-align: center; font-size: 30px; font-weight: bold; letter-spacing: 8px; color: #111827;">{{ $code }}</div>
@endcomponent

O código expira em **{{ $expiresInMinutes }} minutos** e só pode ser usado uma vez.

@component('mail::button', ['url' => route('login'), 'color' => 'primary'])
Ir para o login
@endcomponent

Se você não tentou acessar o painel, ignore este e-mail e **altere sua senha imediatamente**.

Obrigado,<br>
{{ config('app.name') }}

@endcomponent
