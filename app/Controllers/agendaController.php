<?php

class AgendaController {

    private $conexao;

    public function __construct() {
        $this->conexao = conexao();
    }

    public function getEventos() {
        header('Content-Type: application/json');
        $clienteId = $_GET['cliente_id'] ?? null;
        $ano = $_GET['ano'] ?? date('Y');
        $mes = $_GET['mes'] ?? date('m');

        if (!$clienteId) {
            echo json_encode(['success' => false, 'message' => 'ID do cliente é obrigatório.']);
            return;
        }

        try {
            // Buscar a ficha técnica do cliente
            $fichaTecnicaModel = new FichaTecnica();
            $ficha = $fichaTecnicaModel->listarPorCliente($clienteId);
            if (empty($ficha)) {
                echo json_encode(['success' => true, 'data' => []]); // Cliente sem ficha, retorna vazio
                return;
            }
            $fichaId = $ficha[0]['id'];

            // Buscar medicamentos e procedimentos
            $medicamentosModel = new Medicamentos();
            $medicamentos = $medicamentosModel->listarParaAgenda($fichaId, $ano, $mes);

            $procedimentosModel = new ProcedimentosEspecificos();
            $procedimentos = $procedimentosModel->listarParaAgenda($fichaId, $ano, $mes);

            $eventos = array_merge($medicamentos, $procedimentos);

            echo json_encode(['success' => true, 'data' => $eventos]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao buscar eventos: ' . $e->getMessage()]);
        }
    }
}