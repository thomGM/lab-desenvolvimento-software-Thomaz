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
        echo json_encode(['success' => true, 'data' => $listaDeProcedimentos]);
        return;
    }
    public function novoProcedimento() {
        header('Content-Type: application/json');
        parse_str($_POST['formData'], result: $formData);

        $name = $formData['nome'] ?? '';
        $procedimentoModel = new Procedimento();
        $procedimentoModel->nome = $name;
        $procedimentoModel->salvar();
        echo json_encode(['success' => true, 'message' => 'Procedimento salvo com sucesso!']);
        return;
    }
    public function getProcedimentoPorId() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? '';
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Não encontramos o ID']);
        }
        $procedimentoModel = new Procedimento();
        $procedimento = $procedimentoModel->buscarPorId($id);
        echo json_encode(['success' => true, 'data' => $procedimento]);
        return;
    }
    public function alterarProcedimento() {
        header('Content-Type: application/json');
        $id = $_POST['id'] ?? '';
        $nome = $_POST['nome'] ?? '';

        if (empty($id) || empty($nome)) {
            echo json_encode(['success' => false, 'message' => 'ID e nome são obrigatórios']);
            return;
        }

        $procedimentoModel = new Procedimento();
        $procedimentoModel->id = $id;
        $procedimentoModel->nome = $nome;
        $resultado = $procedimentoModel->alterar();

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Procedimento alterado com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao alterar procedimento']);
        }
        return;
    }
    public function inativarProcedimento() {
        header('Content-Type: application/json');
        $id = $_POST['id'] ?? '';

        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'ID é obrigatório']);
            return;
        }

        $procedimentoModel = new Procedimento();
        $procedimentoModel->id = $id;
        $resultado = $procedimentoModel->inativar();

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Procedimento inativado com sucesso']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao inativar procedimento']);
        }
        return;
    }
}