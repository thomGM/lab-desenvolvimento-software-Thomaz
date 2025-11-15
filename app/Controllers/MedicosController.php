<?php

class MedicosController {

    private $conexao;

    public function __construct() {
        $this->conexao = conexao();
    }

    public function listar() {
        header('Content-Type: application/json');
        $medicosModel = new Medicos();
        $listaDeMedicos = $medicosModel->consulta();
        echo json_encode($listaDeMedicos);
        return;
    }
    public function novoMedico() {
        header('Content-Type: application/json');
        
        $name = $_POST['nome'] ?? '';
        $medicosModel = new Medicos();
        $medicosModel->nome = $name;
        $medicosModel->crm = $_POST['crm'] ?? '';
        $medicosModel->especialidade = $_POST['especialidade'] ?? '';
        $medicosModel->telefone = $_POST['telefone'] ?? '';
        $medicosModel->salvar();
        echo json_encode(['success' => true, 'message' => 'Médico salvo com sucesso!']);
        return;
    }
}
