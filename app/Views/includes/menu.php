<link rel="stylesheet" href="css/style.css">

<nav class="sidebar" id="sidebar" aria-label="menu lateral">
    <button class="desabilitar" id="desabilitar" onclick="desabilitar()" aria-label="Minimizar menu">&lt;</button>
    <ul>
        <li><a href="/home">Início</a></li>
        <li><a href="/clientes.php">Clientes</a></li>
        <li><a href="/services">Serviços</a></li>
        <li><a href="/contact">Contato</a></li>
    </ul>
</nav>

<!-- botão para reabrir o menu quando a sidebar estiver escondida -->
<button id="openSidebar" class="open-sidebar" onclick="toggleSidebar()" aria-label="Abrir menu">›</button>

<script src="js/jquery/jquery-3.7.1.min.js" defer></script>
<script src="js/menu.js" defer></script>