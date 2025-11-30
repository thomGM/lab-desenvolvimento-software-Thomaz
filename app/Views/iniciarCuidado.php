<?php 
    include 'includes/global.php';
    $dataBr = date("d/m/Y");
    $date = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Cuidado</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/iniciarCuidado.css">
</head>
<body>
    <?php include 'includes/menu.php'; ?>

    <main class="content">
        <h1>Agenda - <?= $dataBr ?></h1>
        <input type="hidden" id="dataDia" value="<?= $date ?>">
        
        <div class="tasks-container">
            <!-- Tarefas serão carregadas dinamicamente via AJAX -->
        </div>
        
        <script>
            var CLIENTE_ID = <?= isset($_GET['cliente_id']) ? $_GET['cliente_id'] : '1' ?>;
        </script>
    </main>
    <script>var CLIENTE_ID= <?= $_GET['id'] ?></script>
    <script src="<?= BASE_URL ?>/js/iniciarCuidado.js"></script>
</body>
</html>