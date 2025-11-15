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
            $clienteModel->cpf = $_POST['cpf'];
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

                        if (isset($med['diasSemana'])) {
                            $diasMedicamento = implode(',', $med['diasSemana']);
                        } else if (isset($med['intervaloDias'])) {
                            $diasMedicamento = $med['diasMedicamento'];
                        } else {
                            $diasMedicamento = '';
                        }

                        if (isset($med['horasIntervalo'])) {
                            $horasMedicamento = $med['horasIntervalo'];
                        } else if (isset($med['minutosIntervalo'])) {
                            $horasMedicamento = $med['minutosIntervalo'];
                        } else if (isset($med['horariosEspecificos'])) {
                            $horasMedicamento = $med['horariosEspecificos'];
                        } else {
                            $horasMedicamento = '';
                        }

                        if (!empty($med['nome'])) {
                            $medicamentosModel = new Medicamentos();
                            $medicamentosModel->id_ficha = $fichaTecnicaModel->getId();
                            $medicamentosModel->nome = $med['nome'];
                            $medicamentosModel->dosagem = $med['dosagem'] ?? '';
                            $medicamentosModel->viaAdministracao = $med['viaAdministracao'] ?? '';
                            $medicamentosModel->inicioTratamento = ($med['dataInicio'] ?? '') . ' ' . ($med['horaInicio'] ?? '');
                            $medicamentosModel->fimTratamento = ($med['dataFim'] ?? '') . ' ' . ($med['horaFim'] ?? '');
                            $medicamentosModel->repetir = $med['repetir'] ?? '';
                            $medicamentosModel->intervalo = $med['intervalo'] ?? '';
                            $medicamentosModel->ultima_aplicacao = ($med['ultima_aplicacao_data'] ?? '') . ' ' . ($med['ultima_aplicacao_hora'] ?? '');
                            $medicamentosModel->diasMedicamento =  $diasMedicamento;
                            $medicamentosModel->horasMedicamento = $horasMedicamento;
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

    public function atualizar() {
        // Similar to novoCliente but fetches existing data and updates instead of inserting
        header('Content-Type: application/json');
        
        parse_str($_POST['formData'], $formData);
        $id = $formData['id'] ?? null;

        if (empty($id) || empty($formData)) {
            echo json_encode(['success' => false, 'message' => 'ID e dados do formulário são obrigatórios!']);
            return;
        }

        try {
            $this->conexao->beginTransaction();

            // 1. Atualizar dados do cliente (paciente)
            $clienteModel = new Clientes();
            $clienteModel->id = $id;
            $clienteModel->nome = $formData['nome'];
            $clienteModel->dataNascimento = $formData['dataNascimento'];
            $clienteModel->endereco = $formData['endereco'];
            $clienteModel->telefone = $formData['telefone'];
            $clienteModel->telefoneEmergencia = $formData['telefoneEmergencia'];
            $clienteModel->cpf = $formData['cpf'];
            $clienteModel->atualizar();

            // 2. Buscar ou criar a ficha técnica e obter seu ID
            $fichaTecnicaModel = new FichaTecnica();
            $fichaExistente = $fichaTecnicaModel->listarPorCliente($id);
            $fichaId = null;

            if (!$fichaExistente) {
                // Se não existir, cria uma nova
                $fichaTecnicaModel->paciente = $id;
                $fichaTecnicaModel->historicoMedico = isset($formData['historicoMedico']) ? 1 : 0;
                $fichaTecnicaModel->medicamentos = isset($formData['medicamentos']) ? 1 : 0;
                $fichaTecnicaModel->restricoesAlimentares = isset($formData['restricoesAlimentares']) ? 1 : 0;
                $fichaTecnicaModel->procedimentosEspecificos = isset($formData['procedimentosEspecificos']) ? 1 : 0;
                $fichaTecnicaModel->salvar();
                $fichaId = $fichaTecnicaModel->getId();
            } else {
                // Se já existir, obtém o ID e atualiza
                $fichaId = $fichaExistente[0]['id'];
                $fichaTecnicaModel->id = $fichaId;
                $fichaTecnicaModel->paciente = $id;
                $fichaTecnicaModel->historicoMedico = isset($formData['historicoMedico']) ? 1 : 0;
                $fichaTecnicaModel->medicamentos = isset($formData['medicamentos']) ? 1 : 0;
                $fichaTecnicaModel->restricoesAlimentares = isset($formData['restricoesAlimentares']) ? 1 : 0;
                $fichaTecnicaModel->procedimentosEspecificos = isset($formData['procedimentosEspecificos']) ? 1 : 0;
                $fichaTecnicaModel->atualizar();
            }

            // 3. Limpar dados antigos e inserir os novos (usando o $fichaId obtido)
            if ($fichaId) {
                // Histórico Médico
                (new HistoricoMedico())->deletar($fichaId);
                if (isset($formData['historicoMedico']) && is_array($formData['historicoMedico'])) {
                    foreach ($formData['historicoMedico'] as $historico) {
                        if (!empty($historico['id_medico'])) {
                            $historicoMedicoModel = new HistoricoMedico();
                            $historicoMedicoModel->id_ficha = $fichaId;
                            $historicoMedicoModel->descricao = $historico['descricao'] ?? '';
                            $historicoMedicoModel->id_medico = $historico['id_medico'];
                            $historicoMedicoModel->salvar();
                        }
                    }
                }

                // Medicamentos
                (new Medicamentos())->deletar($fichaId);
                if (isset($formData['medicamentos']) && is_array($formData['medicamentos'])) {
                    foreach ($formData['medicamentos'] as $med) {
                        if (!empty($med['nome'])) {
                            $diasMedicamento = '';
                            if (isset($med['diasSemana'])) $diasMedicamento = implode(',', $med['diasSemana']);
                            else if (isset($med['intervaloDias'])) $diasMedicamento = $med['intervaloDias'];
                            
                            $horasMedicamento = '';
                            if (isset($med['horasIntervalo'])) $horasMedicamento = $med['horasIntervalo'];
                            else if (isset($med['minutosIntervalo'])) $horasMedicamento = $med['minutosIntervalo'];
                            else if (isset($med['horariosEspecificos'])) $horasMedicamento = $med['horariosEspecificos'];
                            
                            $medicamentosModel = new Medicamentos();
                            $medicamentosModel->id_ficha = $fichaId;
                            $medicamentosModel->nome = $med['nome'];
                            $medicamentosModel->dosagem = $med['dosagem'] ?? '';
                            $medicamentosModel->viaAdministracao = $med['viaAdministracao'] ?? ''; // Corrigido para o nome do campo no JS
                            $medicamentosModel->inicioTratamento = $med['dataInicio'] ?? null;
                            $medicamentosModel->fimTratamento = $med['dataFim'] ?? null;
                            $medicamentosModel->repetir = $med['repetir'] ?? null;
                            $medicamentosModel->intervalo = $med['intervalo'] ?? null;
                            $medicamentosModel->ultima_aplicacao = (!empty($med['ultima_aplicacao_data']) && !empty($med['ultima_aplicacao_hora'])) ? ($med['ultima_aplicacao_data'] . ' ' . $med['ultima_aplicacao_hora']) : null;
                            $medicamentosModel->diasMedicamento = $diasMedicamento;
                            $medicamentosModel->horasMedicamento = $horasMedicamento;
                            $medicamentosModel->salvar();
                        }
                    }
                }
                
                // Restrições Alimentares
                (new RestricoesAlimentares())->deletar($fichaId);
                if (isset($formData['restricoesAlimentares']) && is_array($formData['restricoesAlimentares'])) {
                    foreach ($formData['restricoesAlimentares'] as $restricao) {
                        if (!empty($restricao['descricao'])) {
                            $restricaoAlimentarModel = new RestricoesAlimentares();
                            $restricaoAlimentarModel->id_ficha = $fichaId;
                            $restricaoAlimentarModel->descricao = $restricao['descricao'];
                            $restricaoAlimentarModel->salvar();
                        }
                    }
                }
                
                // Procedimentos Específicos
                (new ProcedimentosEspecificos())->deletar($fichaId);
                if (isset($formData['procedimentosEspecificos']) && is_array($formData['procedimentosEspecificos'])) {
                    foreach ($formData['procedimentosEspecificos'] as $proc) {
                        if (!empty($proc['id_procedimento'])) {
                            $procedimentosEspecificosModel = new ProcedimentosEspecificos();
                            $procedimentosEspecificosModel->id_ficha = $fichaId;
                            $procedimentosEspecificosModel->id_procedimento = $proc['id_procedimento'];
                            $procedimentosEspecificosModel->horarios = $proc['hora'] ?? null;
                            $procedimentosEspecificosModel->descricao = $proc['descricao'] ?? '';
                            $procedimentosEspecificosModel->salvar();
                        }
                    }
                }
            }
            
            $this->conexao->commit();
            echo json_encode(['success' => true, 'message' => 'Cliente atualizado com sucesso!']);
        } catch (Exception $e) {
            $this->conexao->rollBack();
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o cliente: ' . $e->getMessage()]);
        }   
    }
}
