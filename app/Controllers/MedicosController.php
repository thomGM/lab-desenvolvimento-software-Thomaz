<?php
require_once __DIR__ . '/../Core/log.php';

class MedicosController {

    private $conexao;

    public function __construct() {
        $this->conexao = conexao();
    }

    public function listar() {
        header('Content-Type: application/json');
        $medicosModel = new Medicos();
        $listaDeMedicos = $medicosModel->consulta();
        echo json_encode(['success' => true, 'data' => $listaDeMedicos]);
        return;
    }
    public function novoMedico() {
        header('Content-Type: application/json');
        parse_str($_POST['formData'], $formData);

        $name = $formData['nome'] ?? '';
        $medicosModel = new Medicos();
        $medicosModel->nome = $name;
        $medicosModel->crm = $formData['crm'] ?? '';
        $medicosModel->especialidade = $formData['especialidade'] ?? '';
        $medicosModel->telefone = $formData['telefone'] ?? '';
        $medicosModel->salvar();
        echo json_encode(['success' => true, 'message' => 'Médico salvo com sucesso!']);
        return;
    }
    public function getMedicoPorId() {
        $medicoId = $_GET['id'] ?? null;
        if (!$medicoId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'ID do médico não fornecido']);
            return;
        }
        header('Content-Type: application/json');
        $medicosModel = new Medicos();
        $medico = $medicosModel->buscarPorId($medicoId);
        if ($medico) {
            echo json_encode(['success' => true, 'data' => $medico]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Médico não encontrado']);
        }
        return;
    }
    public function alterarMedico() {
        header('Content-Type: application/json');
        parse_str($_POST['formData'], $formData);
        $medicoId = $formData['id_medico'] ?? null;

        log_error(print_r($formData, true));
        log_error($medicoId);

        if (!$medicoId) {
            echo json_encode(['success' => false, 'message' => 'ID do médico não fornecido']);
            return;
        }

        $medicosModel = new Medicos();
        $medicosModel->id = $medicoId;
        $medicosModel->nome = $formData['nome'] ?? '';
        $medicosModel->crm = $formData['crm'] ?? '';
        $medicosModel->especialidade = $formData['especialidade'] ?? '';
        $medicosModel->telefone = $formData['telefone'] ?? '';

        $sucesso = $medicosModel->alterar();
        if ($sucesso) {
            echo json_encode(['success' => true, 'message' => 'Médico alterado com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Falha ao alterar o médico.']);
        }
        return;
    }
}
