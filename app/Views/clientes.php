<?php 
    include 'includes/global.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/padraoPesquisa.css">
    <title>Clientes</title>
</head>
<body>
    <?php include __DIR__ . '/includes/menu.php'; ?>
    <main class="content">
        <h1>Clientes</h1>
        <label for="tipo-pesquisa"><select id="tipo-pesquisa" name="tipo-pesquisa">
            <option value="nome">Nome</option>
            <option value="CPF">CPF</option>
        </select></label>
        <input type="text" id="cliente-nome" name="cliente-nome">
        <button type="button" onclick="pesquisar()">Buscar</button>
        <button type="button" onclick="incluirNovo()">Incluir Novo +</button>

        <div>
            <table id="tabela-clientes">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone de Emergência</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
<script src="<?= BASE_URL ?>/js/clientesPesquisar.js"></script>