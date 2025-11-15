<?php

class ProcedimentoController {

    private $conexao;

    public function __construct() {
        $this->conexao = conexao();
    }

    public function listar() {
        header('Content-Type: application/json');
        $procedimentoModel = new Procedimento();
        $listaDeProcedimentos = $procedimentoModel->consulta();
        echo json_encode($listaDeProcedimentos);
        return;
    }
    public function novoProcedimento() {
        header('Content-Type: application/json');
        
        $name = $_POST['nome'] ?? '';
        $procedimentoModel = new Procedimento();
        $procedimentoModel->nome = $name;
        $procedimentoModel->salvar();
        echo json_encode(['success' => true, 'message' => 'Procedimento salvo com sucesso!']);
        return;
    }
}
