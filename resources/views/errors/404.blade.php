<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Página não encontrada</title>
    <style>
        body {
            font-family: sans-serif;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .box {
            text-align: center;
        }

        h1 {
            font-size: 80px;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>404</h1>
        <p>Página não encontrada</p>

        <a href="{{ url('/') }}">
            Voltar ao início
        </a>
    </div>
</body>
</html>