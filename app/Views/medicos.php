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
    <h1 class="mb-4">Gerenciamento de Médicos</h1>

    <div class="card mb-5">
        <div class="card-header">
            <h3>Cadastrar Novo Médico</h3>
        </div>
        <div class="card-body">
            <form id="formMedico" method="POST">
                <input type="hidden" name="id" value="">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nome" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Ex: Dr. João da Silva" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="crm" class="form-label">CRM</label>
                        <input type="text" class="form-control" id="crm" name="crm" placeholder="Ex: 123456/SP" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="especialidade" class="form-label">Especialidade</label>
                        <input type="text" class="form-control" id="especialidade" name="especialidade" placeholder="Ex: Cardiologia">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Ex: (11) 99999-8888">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Ex: joao.silva@email.com">
                </div>

                <button type="button" onclick="novoMedico()" class="btn btn-success">Cadastrar</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Médicos Cadastrados</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" id="tabelaMedicos">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CRM</th>
                        <th>Especialidade</th>
                        <th>Telefone</th>
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
<script src="<?= BASE_URL ?>/js/medico.js"></script>
    
</body>
</html>