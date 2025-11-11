<?php

class ClientesController {

    private $conexao;

    public function __construct() {
        $this->conexao = conexao();
    }

   /* public function listar() {
        
        $usuarioModel = new Clientes($this->conexao);
        $listaDeUsuarios = $usuarioModel->listarTodos(); 

    }*/

    public function buscarPorNome() {
        // 1. Validar a entrada
        $nome = $_GET['nome'] ?? '';
        if (empty($nome)) {
            echo "Nome inválido!";
            return;
        }

        // 2. Chamar o Model
        $usuarioModel = new Clientes($this->conexao);
        $usuario = $usuarioModel->buscar($nome);

        // 3. Chamar a View
        //require_once '../views/usuarios/detalhe.php';
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

            require_once __DIR__ . '/../Core/log.php';
            log_error('Cliente salvo com ID: ' . $clienteModel->getId());

            if ($clienteModel->getId()) {
                // 3. Processar histórico médico (múltiplos)
                if (isset($_POST['historicoMedico']) && is_array($_POST['historicoMedico'])) {
                    foreach ($_POST['historicoMedico'] as $historico) {
                        if (!empty($historico['id_medico'])) {
                            $historicoMedicoModel = new HistoricoMedico();
                            $historicoMedicoModel->id_ficha = $clienteModel->getId();
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
                            $medicamentosModel->id_ficha = $clienteModel->getId();
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
                            $restricaoAlimentarModel->id_ficha = $clienteModel->getId();
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
                            $procedimentosEspecificosModel->id_ficha = $clienteModel->getId();
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
}