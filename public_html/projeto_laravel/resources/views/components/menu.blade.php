<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('/css/menu.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Menu</title>
</head>
<body>
        <div class="capa">
            <span class="angel">Eleonora Boutique</span>
        </div>
        <div class="linha"></div>
        <div  class="menu">
            <div class="opcao" id="feminino">
                Feminino
                <div class="submenu">
                    <span class="menuOp"><a href="{{ route('roupas.modas', ['modulo' => 1, 'tipo' => 1]) }}" class="link">Camisas</a></span></br>
                    <span class="menuOp"><a href="{{ route('roupas.modas', ['modulo' => 1, 'tipo' => 2]) }}" class="link">Calça</a></span></br>
                    <span class="menuOp"><a href="{{ route('roupas.modas', ['modulo' => 1, 'tipo' => 3]) }}" class="link">Casaco</a></span>
                </div>
            </div>
            <div class="opcao" id="masculino">
                Masculino
                <div class="submenu1">
                    <span class="menuOp"><a href="{{ route('roupas.modas', ['modulo' => 2, 'tipo' => 1]) }}" class="link">Camisas</a></span></br>
                    <span class="menuOp"><a href="{{ route('roupas.modas', ['modulo' => 2, 'tipo' => 2]) }}" class="link">Calça</a></span></br>
                    <span class="menuOp"><a href="{{ route('roupas.modas', ['modulo' => 2, 'tipo' => 3]) }}" class="link">Casaco</a></span>
                </div>
            </div> 
            <span class="opcao"><a href="{{ route('roupas.modas', ['modulo' => 3, 'tipo' => 0]) }}" class="link">Calçados e Acessorios</a></span> 
            <span class="opcao"><a href="{{ route('roupas.modas', ['modulo' => 5, 'tipo' => 0]) }}" class="link">Ofertas</a></span> 
            <span class="opcao"><a href="{{ route('roupas.modas', ['modulo' => 4, 'tipo' => 0]) }}" class="link">Lançamentos</a></span> 
            <div class="conta" id="conta">
                Conta
                <div class="submenu2">
                    <span class="menuOp"><a href="{{ route('pag.login') }}" class="link">Entrar</a></span></br>
                    <span class="menuOp"><a href="{{ route('pag.fotos') }}" class="link">Roupas</a></span></br>
                    <span class="menuOp"><a href="{{ route('pag.carrinho') }}" class="link">Carrinho</a></span>
                </div>
            </div>
        </div>
        <div class="menu1">
            <div class="menu-icon" id="menu-icon">&#9776;</div>
            <div id="teste">
            <ul class="elementos" id="elementos">
            <li class="font">
                <a href="#" class="href">
                    <i class="fas fa-user"></i> Conta
                </a>
            </li>
                <li class="font">Feminino <i class="fas fa-chevron-down"></i></li>
                <li class="font">Masculino <i class="fas fa-chevron-down"></i></li>
                <li class="font">Calçados e Acessorios <i class="fas fa-chevron-down"></i></li>
                <li class="font">Ofertas <i class="fas fa-chevron-down"></i></li>
                <li class="font">Lançamentos <i class="fas fa-chevron-down"></i></li>
            </ul>
            </div>
        </div>

        <script src="{{ asset('assets/jquery.js') }}"></script>
        <script src="{{ asset('js/menu.js') }}"></script>

</body>
</html>