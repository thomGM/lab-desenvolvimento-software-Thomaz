<?php 
	include 'includes/global.php';
    require_once __DIR__ . '/../Core/autoload.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?= BASE_URL ?>/css/novoCliente.css">
	<title>Editar Cliente</title>
    <script>var CLIENTE_ID = <?= $_GET['id'] ?></script>
</head>
<body>
	<?php include 'includes/menu.php'; ?>
	<main class="content">
		<div class="form-container">
			<form id="formCliente">
				<h2>Editar Dados Pessoais</h2>
				<div class="form-group">
					<label for="nome" class="required">Nome</label>
					<input type="text" class="form-control" id="nome" name="nome" required="">
				</div>
				<div class="form-group">
					<label for="cpf" class="required">CPF</label>
					<input type="text" class="form-control" id="cpf" name="cpf" required="">
				</div>
				<div class="form-group">
					<label for="dataNascimento" class="required">Data de Nascimento</label>
					<input type="date" class="form-control" id="dataNascimento" name="dataNascimento" required="">
				</div>
				<div class="form-group">
					<label for="endereco" class="required">Endereço</label>
					<input type="text" class="form-control" id="endereco" name="endereco" required="">
				</div>
				<div class="form-group">
					<label for="telefone">Telefone</label>
					<input type="tel" class="form-control" id="telefone" name="telefone">
				</div>
				<div class="form-group">
					<label for="telefoneEmergencia" class="required">Telefone de Emergência</label>
					<input type="tel" class="form-control" id="telefoneEmergencia" name="telefoneEmergencia" required="">
				</div>

		<h2>Ficha Técnica</h2>
		<label for="historicoMedico">Histórico Médico:</label>
		<input type="checkbox" name="historicoMedico" id="historicoMedico"><br>
		<label for="medicamentos">Medicamentos:</label>
		<input type="checkbox" name="medicamentos" id="medicamentos"><br>
		<label for="restricoesAlimentares">Restrições Alimentares:</label>
		<input type="checkbox" name="restricoesAlimentares" id="restricoesAlimentares"><br>
		<label for="procedimentosEspecificos">Procedimentos Específicos:</label>
		<input type="checkbox" name="procedimentosEspecificos" id="procedimentosEspecificos"><br>
       
		<div id="historicoMedicoInfo" class="form-section">
			<div class="section-header">
				<h3>Histórico Médico</h3>
				<button type="button" class="adicionar" onclick="novoHistoricoMedico(1)" aria-label="Adicionar histórico">+</button>
			</div>
			<div id="divHistoricoMedico"></div>
		</div>

		<div id="medicamentosInfo" class="form-section">
			<div class="section-header">
				<h3>Medicamentos</h3>
				<button type="button" class="adicionar" onclick="novoMedicamentos(1)" aria-label="Adicionar medicamento">+</button>
			</div>
			<div id="divMedicamentos"></div>
		</div>

		<div id="restricoesAlimentaresInfo" class="form-section">
			<div class="section-header">
				<h3>Restrições Alimentares</h3>
				<button type="button" class="adicionar" onclick="novoRestricoesAlimentares(1)" aria-label="Adicionar restrição">+</button>
			</div>
			<div id="divRestricoesAlimentares"></div>
		</div>

		<div id="procedimentosEspecificosInfo" class="form-section">
			<div class="section-header">
				<h3>Procedimentos Específicos</h3>
				<button type="button" class="adicionar" onclick="novoProcedimentosEspecificos(1)" aria-label="Adicionar procedimento">+</button>
			</div>
			<div id="divProcedimentosEspecificos"></div>
		</div>

		<div class="button-group">
			<button type="button" class="btn btn-primary" onclick="atualizarCliente(<?= $_GET['id'] ?>)">Salvar Alterações</button>
			<button type="button" class="btn btn-secondary" onclick="cancelar()">Cancelar</button>
		</div>
	</form>
	</div>
	</main>
</body>
</html>
<script src="<?= BASE_URL ?>/js/clientes.js"></script>
<script src="<?= BASE_URL ?>/js/editaCliente.js"></script>
