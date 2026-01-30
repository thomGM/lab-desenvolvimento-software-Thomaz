<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/cadastro.css') }}" rel="stylesheet">
    <title>Document</title>
</head>
<body topmargin="0" leftmargin="0">
    <header>
        @include('components.menu')
    </header>
    <div class="form">
        <form id="formulario" method="POST" action="{{ route('salvar.dados') }}">
        @csrf
            <span>Nome Completo</span></br>
            <input type="text" id="nome" name="nome" class="required" value="{{ old('nome') }}"/></br>
            <span>Data de Nascimento</span></br>
            <input type="date" id="data" name="data" class="required" value="{{ old('data') }}"/></br>
            <span>CPF</span></br>
            <input type="text" id="cpf" name="cpf" class="required" value="{{ old('cpf') }}"/></br>
            <span>E-mail</span></br>
            <input type="text" id="email" name="email" class="required" value="{{ old('email') }}"/></br>
            <span>Celular</span></br>
            <input type="text" id="celular" name="celular" class="required" value="{{ old('celular') }}"/></br>
            <span>Senha</span></br>
            <input type="password" id="senha" name="senha" class="required" value="{{ old('senha') }}"/></br>
            <span>Confirme sua senha</span></br>
            <input type="password" id="senha_confirmation" name="senha_confirmation" class="required" value="{{ old('senha_confirmation') }}"/></br>
            <button type="submit" id="salvar">Salvar</button>
        </form>

        <div id="mensagem" style="display: none;" class="erro">Preencha os campos obrigatórios</div>

        @if($errors->any())
        <div class="erro">
            @foreach ($errors->all() as $error)
                <span>{{ $error }}</span>
    </br>
            @endforeach
        </div>
    @endif

        @if(session('mensagem'))
        <div class="erro">
            {{ session('mensagem') }}
    </br>
        </div>
    @endif
    </div>
   

<script src="{{ asset('assets/jquery.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>