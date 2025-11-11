<?php 
    include 'includes/global.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes</title>
</head>
<body>
    <?php include __DIR__ . '/includes/menu.php'; ?>
    <main class="content">
        <h1>Clientes</h1>
        <label for="nome"><select id="nome" name="nome">
            <option value="nome">Nome</option>
            <option value="CPF">CPF</option>
        </select></label>
        <input type="text" id="cliente-nome" name="cliente-nome">
        <button type="button" onclick="pesquisar()">Buscar</button>
        <button type="button" onclick="incluirNovo()">Incluir Novo +</button>
    </main>
</body>
</html>
<script src="js/clientes.js" defer></script>