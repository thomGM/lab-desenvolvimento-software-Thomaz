<?php 
    include 'includes/global.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Médicos</title>
    <!-- Incluindo Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'includes/menu.php'; ?>

<main class="content">
    <h1 class="mb-4">Procedimentos</h1>

    <div class="card mb-5">
        <div class="card-header">
            <h3>Cadastrar Novo Procedimento</h3>
        </div>
        <div class="card-body">
            <form id="formProcedimento" method="POST">
                <input type="hidden" name="id" value="">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Ex: Exame de Sangue" required>
                    </div>
                </div>
                <button type="button" onclick="novoProcedimento()" class="btn btn-success">Cadastrar</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Procedimentos Cadastrados 
                <select id="filtroStatus" style="font-size: 20px; margin-left: 30%; border-radius: 5px; width: 100px;">
                    <option value="todos">Todos</option>
                    <option value="1">Ativos</option>
                    <option value="0">Inativos</option>
                </select>
            </h3>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" id="tabelaProcedimentos">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Incluindo Bootstrap JS (opcional, para componentes interativos) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="<?= BASE_URL ?>/js/procedimento.js"></script>
    
</body>
</html>