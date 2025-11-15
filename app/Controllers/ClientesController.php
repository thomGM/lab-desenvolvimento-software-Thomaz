<?php

class ClientesController {

    private $conexao;

    public function __construct() {
        $this->conexao = conexao();
    }

    public function listar() {
        header('Content-Type: application/json');
        $clienteModel = new Clientes();
        $listaDeClientes = $clienteModel->listarTodos();
        echo json_encode($listaDeClientes);
        return;
    }

    public function buscar() {
        $nome = $_GET['nome'] ?? '';
        $tipo = $_GET['tipo'];
        if (empty($nome)) {
            echo json_encode(['success' => false, 'message' => "Inclua um nome para busca"]);
            exit;
        }

        $clienteModel = new Clientes();
        $clientes = $clienteModel->buscar($nome, $tipo);
        echo json_encode(['success' => true, 'data' => $clientes]);
        return;
    }

    public function novoCliente() {
        header('Content-Type: application/json');
        
        $name = $_POST['nome'] ?? '';
        $dataNascimento = $_POST['dataNascimento'] ?? '';
        $endereco = $_POST['endereco'] ?? '';
        $telefone = $_POST['telefone'] ?? '';
        $telefoneEmergencia = $_POST['telefoneEmergencia'] ?? '';

        // 1. Validar os dados básicos
        if (empty($name) || empty($dataNascimento) || empty($endereco) || empty($telefoneEmergencia)) {
            echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios!']);
            return;
        }

        try {
            // 2. Chamar o Model para salvar os dados do cliente
            $clienteModel = new Clientes();
            $clienteModel->nome = $name;
            $clienteModel->dataNascimento = $dataNascimento;
            $clienteModel->endereco = $endereco;
            $clienteModel->telefone = $telefone;
            $clienteModel->telefoneEmergencia = $telefoneEmergencia;
            $clienteModel->salvar();

            //require_once __DIR__ . '/../Core/log.php';
            //log_error('Cliente salvo com ID: ' . $clienteModel->getId());

            $fichaTecnicaModel = new FichaTecnica();
            $fichaTecnicaModel->paciente = $clienteModel->getId();
            $fichaTecnicaModel->historicoMedico = isset($_POST['historicoMedico']) ?  1 : 0;
            $fichaTecnicaModel->medicamentos = isset($_POST['medicamentos']) ?  1 : 0;
            $fichaTecnicaModel->restricoesAlimentares = isset($_POST['restricoesAlimentares']) ?  1 : 0;
            $fichaTecnicaModel->procedimentosEspecificos = isset($_POST['procedimentosEspecificos']) ?  1 : 0;
            $fichaTecnicaModel->salvar();

            if ($clienteModel->getId()) {
                // 3. Processar histórico médico (múltiplos)
                if (isset($_POST['historicoMedico']) && is_array($_POST['historicoMedico'])) {
                    foreach ($_POST['historicoMedico'] as $historico) {
                        if (!empty($historico['id_medico'])) {
                            $historicoMedicoModel = new HistoricoMedico();
                            $historicoMedicoModel->id_ficha = $fichaTecnicaModel->getId();
                            $historicoMedicoModel->descricao = $historico['descricao'] ?? '';
                            $historicoMedicoModel->id_medico = $historico['id_medico'];
                            $historicoMedicoModel->salvar();
                        }
                    }
                }

                // 4. Processar medicamentos (múltiplos)
                if (isset($_POST['medicamentos']) && is_array($_POST['medicamentos'])) {
                    foreach ($_POST['medicamentos'] as $med) {
                        if (!empty($med['nome'])) {
                            $medicamentosModel = new Medicamentos();
                            $medicamentosModel->id_ficha = $fichaTecnicaModel->getId();
                            $medicamentosModel->nome = $med['nome'];
                            $medicamentosModel->dosagem = $med['dosagem'] ?? '';
                            $medicamentosModel->frequencia = $med['frequencia'] ?? '';
                            $medicamentosModel->viaAdministracao = $med['via'] ?? '';
                            $medicamentosModel->inicioTratamento = ($med['dataInicio'] ?? '') . ' ' . ($med['horaInicio'] ?? '');
                            $medicamentosModel->fimTratamento = ($med['dataFim'] ?? '') . ' ' . ($med['horaFim'] ?? '');
                            $medicamentosModel->salvar();
                        }
                    }
                }

                // 5. Processar restrições alimentares (múltiplas)
                if (isset($_POST['restricoesAlimentares']) && is_array($_POST['restricoesAlimentares'])) {
                    foreach ($_POST['restricoesAlimentares'] as $restricao) {
                        if (!empty($restricao['descricao'])) {
                            $restricaoAlimentarModel = new RestricoesAlimentares();
                            $restricaoAlimentarModel->id_ficha = $fichaTecnicaModel->getId();
                            $restricaoAlimentarModel->descricao = $restricao['descricao'] ?? '';
                            $restricaoAlimentarModel->salvar();
                        }
                    }
                }

                // 6. Processar procedimentos específicos (múltiplos)
                if (isset($_POST['procedimentosEspecificos']) && is_array($_POST['procedimentosEspecificos'])) {
                    foreach ($_POST['procedimentosEspecificos'] as $proc) {
                        if (!empty($proc['id_procedimento'])) {
                            $procedimentosEspecificosModel = new ProcedimentosEspecificos();
                            $procedimentosEspecificosModel->id_ficha = $fichaTecnicaModel->getId();
                            $procedimentosEspecificosModel->id_procedimento = $proc['id_procedimento'];
                            $procedimentosEspecificosModel->horarios = $proc['hora'] ?? '';
                            $procedimentosEspecificosModel->descricao = $proc['descricao'] ?? '';
                            $procedimentosEspecificosModel->salvar();
                        }
                    }
                }

                echo json_encode(['success' => true, 'message' => 'Cliente cadastrado com sucesso!']);
            } else {
                throw new Exception('Erro ao salvar cliente');
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar o cliente: ' . $e->getMessage()]);
        }
    }

    public function buscarPorId() {
        $id = $_GET['id'] ?? null;

        header('Content-Type: application/json');
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => "ID do cliente é obrigatório"]);
            exit;
        }

        $clienteModel = new Clientes();
        $cliente = $clienteModel->buscar($id, 'id');
        
        $fichaTecnicaModel = new FichaTecnica();
        $fichaTecnica = $fichaTecnicaModel->listarPorCliente($id);

        $historicoMedicoModel = new HistoricoMedico();
        $historicoMedico = $historicoMedicoModel->listarPorCliente($fichaTecnica[0]['id'] ?? 0);

        $medicamentosModel = new Medicamentos();
        $medicamentos = $medicamentosModel->listarPorCliente($fichaTecnica[0]['id'] ?? 0);

        $restricoesAlimentaresModel = new RestricoesAlimentares();
        $restricoesAlimentares = $restricoesAlimentaresModel->listarPorCliente($fichaTecnica[0]['id'] ?? 0);

        $procedimentosEspecificosModel = new ProcedimentosEspecificos();
        $procedimentosEspecificos = $procedimentosEspecificosModel->listarPorCliente($fichaTecnica[0]['id'] ?? 0);

        $result = [
            'cliente' => $cliente,
            'fichaTecnica' => $fichaTecnica,
            'historicoMedico' => $historicoMedico,
            'medicamentos' => $medicamentos,
            'restricoesAlimentares' => $restricoesAlimentares,
            'procedimentosEspecificos' => $procedimentosEspecificos
        ];
        echo json_encode(['success' => true, 'data' => $result]);
        return;
    }
}
