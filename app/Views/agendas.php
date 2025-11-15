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
</head>
<body>  
    <?php include 'includes/menu.php'; ?>

    <main class="content">
        <h1>Agendas dos Clientes</h1>
        <p>Selecione um cliente para visualizar a agenda.</p>
        <div id="clientes-container" class="clientes-container">
            <!-- Os cartões dos clientes serão inseridos aqui pelo JavaScript -->
        </div>
    </main>
    <script src="<?= BASE_URL ?>/js/agendas.js"></script>
</body>
</html>