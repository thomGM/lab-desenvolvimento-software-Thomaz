<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/login.css') }}" rel="stylesheet">

    <title>Login</title>
</head>
<body>
    @include('components.menu')
    <div class="form">
        <form id="formulario" method="POST"  action="{{ route('login') }}">
        @csrf
            <p1>E-mail</p1></br>
            <input type="text" id="email" name="email" value="{{ old('email') }}"/></br>
            <span>Senha</span></br>
            <input type="text" id="senha" name="senha" value="{{ old('senha') }}"/></br>
            <button id="entrar">Entrar</button></br>
        </form>
        <span><a href="{{ route('pag.cadastro') }}">Cadastre-se</a></span>

        @if(session('mensagem'))
        <div class="erro">
            {{ session('mensagem') }}
    </br>
        </div>
    @endif
    </div>
</body>
</html>