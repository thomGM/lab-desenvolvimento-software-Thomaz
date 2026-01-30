<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/fotos.css') }}" rel="stylesheet">
    <title>{{ $title }}</title>
</head>
</head>
<body  topmargin="0" leftmargin="0">
    <header>
        @include('components.menu')
    </header>
    
    
        @foreach ($roupas as $roupa)
        <div class="fotoPai">
            <img class="foto" src="{{ asset('storage/img/' . $roupa->img) }}" alt="img" width="200" height="300"></br>
            <div class="descricao"><a href="{{route('roupas.compra', ['id' => $roupa->id_roupa])}}" class="descr">{{ $roupa->descricao }}</a></div>
        </div>
        @endforeach    
</body>    
</html>