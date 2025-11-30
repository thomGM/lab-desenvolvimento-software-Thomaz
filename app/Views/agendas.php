<?php 
    include 'includes/global.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/agendas.css">
    <title>Agendas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>  
    <?php include 'includes/menu.php'; ?>

    <main class="content">
        <h1>Agendas dos Clientes</h1>
        <p>Selecione um cliente para visualizar a agenda.</p>

        <div class="search-container">
            <input type="text" id="search-cliente" placeholder="Pesquisar cliente por nome...">
        </div>

        <div id="clientes-container" class="clientes-container">
            <!-- Os cartões dos clientes serão inseridos aqui pelo JavaScript -->
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="<?= BASE_URL ?>/js/agendas.js"></script>
</body>
</html>