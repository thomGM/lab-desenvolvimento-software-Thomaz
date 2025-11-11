<link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

<nav class="sidebar" id="sidebar" aria-label="menu lateral">
    <button class="desabilitar" id="desabilitar" onclick="desabilitar()" aria-label="Minimizar menu">&lt;</button>
    <ul>
        <li><a href="index.php">Início</a></li>
        <li class="has-submenu">
            <a href="#" class="submenu-trigger">Clientes <span class="arrow">▼</span></a>
            <ul class="submenu">
                <li><a href="novoCliente.php">Cadastrar Cliente</a></li>
            </ul>
        </li>
        <li><a href="services.php">Serviços</a></li>
        <li><a href="contact.php">Contato</a></li>
    </ul>
</nav>

<!-- botão para reabrir o menu quando a sidebar estiver escondida -->
<button id="openSidebar" class="open-sidebar" onclick="toggleSidebar()" aria-label="Abrir menu">›</button>
<script src="<?= BASE_URL ?>/js/jquery/jquery-3.7.1.min.js"></script>
<script src="<?= BASE_URL ?>/js/menu.js" defer></script>