<?php 
    include 'includes/global.php';
    $clienteId = $_GET['id'] ?? null;
    if (!$clienteId) {
        die("ID do cliente não fornecido.");
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda do Cliente</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/agendaCliente.css">
    <script>
        const CLIENTE_ID = <?= json_encode($clienteId); ?>;
    </script>
</head>
<body>
    <?php include 'includes/menu.php'; ?>
    <main class="content">
        <div class="calendar-header">
            <button id="prev-month">‹</button>
            <h1 id="month-year"></h1>
            <button id="next-month">›</button>
        </div>
        <div id="calendar-grid" class="calendar-grid"></div>
    </main>
    <script src="<?= BASE_URL ?>/js/agendaCliente.js"></script>
</body>
</html>