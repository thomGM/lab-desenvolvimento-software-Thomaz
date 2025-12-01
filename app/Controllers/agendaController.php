<?php
require_once __DIR__ . '/../Core/log.php';
class AgendaController {

    private $conexao;

    public function __construct() {
        $this->conexao = conexao();
    }

    public function marcarConcluida() {
        header('Content-Type: application/json');
        $paciente = $_POST['paciente'] ?? null;
        $eventoId = $_POST['evento_id'] ?? null;
        $eventoTipo = $_POST['evento_tipo'] ?? null;
        $hora = $_POST['hora'] ?? null;

        if (!$paciente || !$eventoId || !$eventoTipo) {
            echo json_encode(['success' => false, 'message' => 'Parâmetros obrigatórios']);
            return;
        }

        try {
            $agendaModel = new Agenda();
            $agendaModel->marcarConcluida($paciente, $eventoId, $eventoTipo, $hora);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function desmarcarConcluida() {
        header('Content-Type: application/json');
        $paciente = $_POST['paciente'] ?? null;
        $eventoId = $_POST['evento_id'] ?? null;
        $eventoTipo = $_POST['evento_tipo'] ?? null;

        if (!$paciente || !$eventoId || !$eventoTipo) {
            echo json_encode(['success' => false, 'message' => 'Parâmetros obrigatórios']);
            return;
        }

        try {
            $agendaModel = new Agenda();
            $agendaModel->desmarcarConcluida($paciente, $eventoId, $eventoTipo);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getEventosDia() {
        header('Content-Type: application/json');
        $clienteId = $_GET['cliente_id'] ?? null;
        $data = $_GET['data'] ?? date('Y-m-d');

        log_error('getEventosDia - clienteId: ' . $clienteId . ', data: ' . $data);

        if (!$clienteId) {
            log_error('Cliente ID não fornecido');
            echo json_encode(['success' => false, 'message' => 'ID do cliente é obrigatório.']);
            return;
        }

        try {
            $fichaTecnicaModel = new FichaTecnica();
            $ficha = $fichaTecnicaModel->listarPorCliente($clienteId);
            log_error('Ficha encontrada: ' . json_encode($ficha));
            
            if (empty($ficha)) {
                log_error('Nenhuma ficha encontrada para cliente: ' . $clienteId);
                echo json_encode(['success' => true, 'data' => []]);
                return;
            }
            $fichaId = $ficha[0]['id'];
            log_error('FichaId: ' . $fichaId);

            $medicamentosModel = new Medicamentos();
            $medicamentos = $medicamentosModel->listarPorData($fichaId, $data);
            log_error('Medicamentos encontrados: ' . json_encode($medicamentos));
            
            $eventos = [];

            foreach ($medicamentos as $medicamento) {
                log_error('Processando medicamento: ' . $medicamento['nome']);
                $datasAplicacao = $this->calcularAplicacoesDia($medicamento, $data);
                log_error('Datas de aplicação calculadas: ' . json_encode($datasAplicacao));
                
                foreach ($datasAplicacao as $dataAplicacao) {
                    $eventos[] = [
                        'id' => $medicamento['id'],
                        'tipo' => 'm',
                        'nome' => $medicamento['nome'],
                        'descricao' => $medicamento['dosagem'] . ' - ' . $medicamento['viaAdministracao'],
                        'data_evento' => $dataAplicacao,
                        'horario' => date('H:i', strtotime($dataAplicacao)),
                        'tipo_evento' => 'medicamento'
                    ];
                }
            }

            $procedimentoModel = new Procedimento();
            $procedimentos = $procedimentoModel->listarPorData($fichaId, $data);
            log_error('Procedimentos encontrados: ' . json_encode($procedimentos));
            
            foreach ($procedimentos as $procedimento) {
                $eventos[] = [
                    'id' => $procedimento['id'],
                    'tipo' => 'p',
                    'nome' => $procedimento['nome'],
                    'descricao' => $procedimento['descricao'] ?? '',
                    'data_evento' => $data . ' ' . ($procedimento['horarios'] ?? '00:00:00'),
                    'horario' => $procedimento['horarios'] ?? '00:00',
                    'tipo_evento' => 'procedimento'
                ];
            }

            log_error('Total de eventos: ' . count($eventos));
            log_error('Eventos finais: ' . json_encode($eventos));

            usort($eventos, function($a, $b) {
                return strtotime($a['data_evento']) - strtotime($b['data_evento']);
            });

            // Verificar quais tarefas já foram concluídas
            $agendaModel = new Agenda();
            $tarefasConcluidas = $agendaModel->verificarConcluidas($clienteId, $data);
            
            // Marcar eventos como concluídos
            $eventoInicio = false;
            $eventoFim = false;
            foreach ($tarefasConcluidas as $concluida) {

                log_error('tarefas: ' . $concluida['evento_tipo']);
                if ($concluida['evento_tipo'] == 'i') {
                    $eventoInicio = true;
                }

                if ($concluida['evento_tipo'] == 'f') {
                    $eventoFim = true;
                }
            }
            foreach ($eventos as &$evento) {
                $evento['concluida'] = false;
                foreach ($tarefasConcluidas as $concluida) {
                    log_error('evento_id ' . $concluida['evento_id']);
                    log_error('evento_id ' . $concluida['evento_id']);
                    log_error('id ' . $evento['id']);
                    log_error('evento_tipo ' . $concluida['evento_tipo']);
                    log_error('tipo ' . $evento['tipo']);
                    log_error('horaAgendada ' . $concluida['horaAgendada']);
                    log_error('horario ' . $evento['horario']);

                    if ($concluida['evento_id'] == $evento['id'] && 
                        $concluida['evento_tipo'] == $evento['tipo'] &&
                        $concluida['horaAgendada'] == $evento['horario'] . ':00') {
                        $evento['concluida'] = true;
                        break;
                    }
                }
            }

            echo json_encode(['success' => true, 'data' => $eventos, 'inicio' => $eventoInicio, 'fim' => $eventoFim]);
        } catch (Exception $e) {
            log_error('Erro em getEventosDia: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erro ao buscar eventos: ' . $e->getMessage()]);
        }
    }

    private function calcularAplicacoesDia($medicamento, $data) {
        $datasAplicacao = [];
        $dataAlvo = new DateTime($data);
        
        log_error('calcularAplicacoesDia - medicamento: ' . $medicamento['nome'] . ', data: ' . $data);
        
        if (empty($medicamento['inicioTratamento'])) {
            log_error('Medicamento sem data de início de tratamento');
            return $datasAplicacao;
        }
        
        $inicioTratamento = new DateTime($medicamento['inicioTratamento']);
        $fimTratamento = new DateTime($medicamento['fimTratamento']);
        
        log_error('Período tratamento: ' . $inicioTratamento->format('Y-m-d') . ' a ' . $fimTratamento->format('Y-m-d'));
        
        if ($dataAlvo < $inicioTratamento || $dataAlvo > $fimTratamento) {
            log_error('Data alvo fora do período de tratamento');
            return $datasAplicacao;
        }

        log_error('Repetir: ' . $medicamento['repetir'] . ', Intervalo: ' . $medicamento['intervalo']);
        log_error('Horas medicamento: ' . $medicamento['horasMedicamento']);

        if ($medicamento['repetir'] == 1) { // Todos os dias
            if ($medicamento['intervalo'] == 3) { // Horas específicas
                log_error('Processando horas específicas para todos os dias');
                $horarios = explode(',', $medicamento['horasMedicamento']);
                foreach ($horarios as $horario) {
                    $horario = trim($horario);
                    $horario = str_replace('::', ':', $horario); // Corrige horários malformados
                    if (!empty($horario) && strpos($horario, ':') !== false) {
                        $dataComHora = clone $dataAlvo;
                        $partesHora = explode(':', $horario);
                        $hora = (int)$partesHora[0];
                        $minuto = isset($partesHora[1]) ? (int)$partesHora[1] : 0;
                        $dataComHora->setTime($hora, $minuto, 0);
                        $datasAplicacao[] = $dataComHora->format('Y-m-d H:i:s');
                        log_error('Adicionada aplicação: ' . $dataComHora->format('Y-m-d H:i:s'));
                    }
                }
            }
        } else if ($medicamento['repetir'] == 2) { // Dias específicos
            log_error('Processando dias específicos da semana');
            $diasSemana = explode(',', $medicamento['diasMedicamento']);
            $diaDaSemanaPHP = $dataAlvo->format('N');
            $diaDaSemanaJS = ($diaDaSemanaPHP % 7) + 1;
            
            log_error('Dia da semana atual: ' . $diaDaSemanaJS . ', dias permitidos: ' . implode(',', $diasSemana));
            
            if (in_array($diaDaSemanaJS, $diasSemana) && $medicamento['intervalo'] == 3) {
                $horarios = explode(',', $medicamento['horasMedicamento']);
                foreach ($horarios as $horario) {
                    $horario = trim($horario);
                    $horario = str_replace('::', ':', $horario); // Corrige horários malformados
                    if (!empty($horario) && strpos($horario, ':') !== false) {
                        $dataComHora = clone $dataAlvo;
                        $partesHora = explode(':', $horario);
                        $hora = (int)$partesHora[0];
                        $minuto = isset($partesHora[1]) ? (int)$partesHora[1] : 0;
                        $dataComHora->setTime($hora, $minuto, 0);
                        $datasAplicacao[] = $dataComHora->format('Y-m-d H:i:s');
                        log_error('Adicionada aplicação: ' . $dataComHora->format('Y-m-d H:i:s'));
                    }
                }
            }
        } else if ($medicamento['repetir'] == 3) { // Intervalo de dias
            log_error('Processando intervalo de dias');
            $intervaloEmDias = (int)$medicamento['diasMedicamento'];
            $inicioTratamento = new DateTime($medicamento['inicioTratamento']);
            
            log_error('Intervalo em dias: ' . $intervaloEmDias);
            
            // Calcular se hoje é um dia de aplicação
            $diasDiferenca = $dataAlvo->diff($inicioTratamento)->days;
            
            if ($diasDiferenca % $intervaloEmDias == 0 && $medicamento['intervalo'] == 3) {
                log_error('Hoje é dia de aplicação (diferença: ' . $diasDiferenca . ' dias)');
                $horarios = explode(',', $medicamento['horasMedicamento']);
                foreach ($horarios as $horario) {
                    $horario = trim($horario);
                    $horario = str_replace('::', ':', $horario); // Corrige horários malformados
                    if (!empty($horario) && strpos($horario, ':') !== false) {
                        $dataComHora = clone $dataAlvo;
                        $partesHora = explode(':', $horario);
                        $hora = (int)$partesHora[0];
                        $minuto = isset($partesHora[1]) ? (int)$partesHora[1] : 0;
                        $dataComHora->setTime($hora, $minuto, 0);
                        $datasAplicacao[] = $dataComHora->format('Y-m-d H:i:s');
                        log_error('Adicionada aplicação: ' . $dataComHora->format('Y-m-d H:i:s'));
                    }
                }
            } else {
                log_error('Hoje NÃO é dia de aplicação (diferença: ' . $diasDiferenca . ' dias, intervalo: ' . $intervaloEmDias . ')');
            }
        }
        
        log_error('Total de aplicações calculadas: ' . count($datasAplicacao));
        return $datasAplicacao;
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
            log_error('Medicamentos para agenda: ' . json_encode($medicamentos));
            $eventos = [];

            foreach ($medicamentos as $medicamento) {
                log_error('Processando medicamento: ');
                // Calcular as datas de aplicação com base na última aplicação e frequência
                $datasAplicacao = [];
                if (!empty($medicamento['inicioTratamento'])) {
                    $inicioAplicacao = new DateTime($medicamento['inicioTratamento']);
                    log_error('Ultima aplicacao: ' . $inicioAplicacao->format('Y-m-d H:i'));
                    log_error('Repetir: ' . $medicamento['repetir'] . ' Intervalo: ' . $medicamento['intervalo']);
    
                    // Define o limite para the loop
                    $dataFimTratamento = new DateTime($medicamento['fimTratamento']);
                    $proximaAplicacao = clone $inicioAplicacao;

                    if ($medicamento['repetir'] == 1) { // Todos os dias
                        log_error('Repetir diariamente');
                        if ($medicamento['intervalo'] ==  1) { // Intervalo em horas
                            $intervaloHoras = (int)$medicamento['horasMedicamento'];
                            if ($intervaloHoras > 0) {
                                while ($proximaAplicacao <= $dataFimTratamento) {
                                    $proximaAplicacao->modify("+{$intervaloHoras} hours");
                                    if ($proximaAplicacao <= $dataFimTratamento) {
                                        $datasAplicacao[] = $proximaAplicacao->format('Y-m-d H:i:s');
                                    }
                                }
                            }
    
                        } else if ($medicamento['intervalo'] == 2) { // Intervalo em minutos
                            $intervaloMinutos = (int)$medicamento['horasMedicamento']; // O campo é o mesmo
                            if ($intervaloMinutos > 0) {
                                while ($proximaAplicacao <= $dataFimTratamento) {
                                    $proximaAplicacao->modify("+{$intervaloMinutos} minutes");
                                    if ($proximaAplicacao <= $dataFimTratamento) {
                                        $datasAplicacao[] = $proximaAplicacao->format('Y-m-d H:i:s');
                                    }
                                }
                            }
    
                        } else if ($medicamento['intervalo'] == 3) { // Horas específicas
                            log_error('Repetir em horas específicas');
                            $horarios = explode(',', $medicamento['horasMedicamento']);
                            $dataCorrente = clone $inicioAplicacao;
                            $dataCorrente->setTime(0, 0, 0); // Zera a hora para iterar pelos dias

                            while ($dataCorrente <= $dataFimTratamento) {
                                foreach ($horarios as $horario) {
                                    $horario = trim($horario);
                                    if (!empty($horario)) {
                                        $hora = (int)substr($horario, 0, 2);
                                        $minuto = (int)substr($horario, 3, 2);
                                        $dataEspecifica = clone $dataCorrente;
                                        $dataEspecifica->setTime($hora, $minuto, 0);

                                        // Adiciona apenas se for no futuro em relação à última aplicação e dentro do tratamento
                                        if ($dataEspecifica > $inicioAplicacao && $dataEspecifica <= $dataFimTratamento) {
                                            $datasAplicacao[] = $dataEspecifica->format('Y-m-d H:i:s');
                                        }
                                    }
                                }
                                $dataCorrente->modify('+1 day');
                            }
                        }
                    } else if ($medicamento['repetir'] == 2) { // Dias específicos da semana
                        $diasSemana = explode(',', $medicamento['diasMedicamento']); // Ex: [2, 4, 6] para Seg, Qua, Sex
                        $dataCorrente = clone $inicioAplicacao;
                        $dataCorrente->setTime(0, 0, 0);

                        while ($dataCorrente <= $dataFimTratamento) {
                            $diaDaSemanaPHP = $dataCorrente->format('N'); // 1 (Seg) a 7 (Dom)
                            $diaDaSemanaJS = ($diaDaSemanaPHP % 7) + 1;

                            if (in_array($diaDaSemanaJS, $diasSemana)) {
                                // É um dia válido, agora aplicamos a lógica de intervalo
                                if ($medicamento['intervalo'] == 3) { // Horas específicas
                                    $horarios = explode(',', $medicamento['horasMedicamento']);
                                    foreach ($horarios as $horario) {
                                        $horario = trim($horario);
                                        if (!empty($horario)) {
                                            $hora = (int)substr($horario, 0, 2);
                                            $minuto = (int)substr($horario, 3, 2);
                                            $dataEspecifica = clone $dataCorrente;
                                            $dataEspecifica->setTime($hora, $minuto, 0);
    
                                            if ($dataEspecifica > $inicioAplicacao && $dataEspecifica <= $dataFimTratamento) {
                                                $datasAplicacao[] = $dataEspecifica->format('Y-m-d H:i:s');
                                            }
                                        }
                                    }
                                } else { // Intervalo em horas ou minutos
                                    // Para dias específicos, o primeiro evento do dia começa no início do dia
                                    $proximaDoDia = clone $dataCorrente;
                                    while($proximaDoDia < (clone $dataCorrente)->modify('+1 day') && $proximaDoDia <= $dataFimTratamento) {
                                        if ($proximaDoDia > $inicioAplicacao) {
                                            $datasAplicacao[] = $proximaDoDia->format('Y-m-d H:i:s');
                                        }
                                        if ($medicamento['intervalo'] == 1 && (int)$medicamento['horasMedicamento'] > 0) { // Horas
                                            $proximaDoDia->modify('+' . (int)$medicamento['horasMedicamento'] . ' hours');
                                        } else if ($medicamento['intervalo'] == 2 && (int)$medicamento['horasMedicamento'] > 0) { // Minutos
                                            $proximaDoDia->modify('+' . (int)$medicamento['horasMedicamento'] . ' minutes');
                                        }
                                    }
                                }
                            }
                            $dataCorrente->modify('+1 day');
                        }
                    } else if ($medicamento['repetir'] == 3) { // Intervalo de dias
                        $intervaloEmDias = (int)$medicamento['diasMedicamento'];
                        log_error('Repetir a cada ' . $intervaloEmDias . ' dias');
                        
                        if ($intervaloEmDias > 0) {
                            $dataCorrente = clone $inicioAplicacao;
                            
                            while ($dataCorrente <= $dataFimTratamento) {
                                $dataCorrente->modify("+" . $intervaloEmDias . " days");
                                
                                if ($dataCorrente <= $dataFimTratamento) {
                                    // Para este dia, aplicamos a lógica de horários
                                    if ($medicamento['intervalo'] == 3) { // Horas específicas
                                        $horarios = explode(',', $medicamento['horasMedicamento']);
                                        foreach ($horarios as $horario) {
                                            $horario = trim($horario);
                                            if (!empty($horario)) {
                                                $hora = (int)substr($horario, 0, 2);
                                                $minuto = (int)substr($horario, 3, 2);
                                                $dataEspecifica = clone $dataCorrente;
                                                $dataEspecifica->setTime($hora, $minuto, 0);
        
                                                if ($dataEspecifica <= $dataFimTratamento) {
                                                    $datasAplicacao[] = $dataEspecifica->format('Y-m-d H:i:s');
                                                }
                                            }
                                        }
                                    }
                                } else { // Intervalo em horas ou minutos
                                    $proximaDoDia = clone $dataCorrente;
                                    while($proximaDoDia < (clone $dataCorrente)->modify('+1 day') && $proximaDoDia <= $dataFimTratamento) {
                                        if ($proximaDoDia > $inicioAplicacao) {
                                            $datasAplicacao[] = $proximaDoDia->format('Y-m-d H:i:s');
                                    } else { // Intervalo em horas ou minutos
                                        $proximaDoDia = clone $dataCorrente;
                                        $proximaDoDia->setTime(0, 0, 0); // Começa à meia-noite do dia calculado
                                        while($proximaDoDia < (clone $dataCorrente)->modify('+1 day') && $proximaDoDia <= $dataFimTratamento) {
                                            $datasAplicacao[] = $proximaDoDia->format('Y-m-d H:i:s');
                                            if ($medicamento['intervalo'] == 1 && (int)$medicamento['horasMedicamento'] > 0) { // Horas
                                                $proximaDoDia->modify('+' . (int)$medicamento['horasMedicamento'] . ' hours');
                                            } else if ($medicamento['intervalo'] == 2 && (int)$medicamento['horasMedicamento'] > 0) { // Minutos
                                                $proximaDoDia->modify('+' . (int)$medicamento['horasMedicamento'] . ' minutes');
                                            } else {
                                                break; // Evita loop infinito se o intervalo for 0
                                            }
                                        }
                                    }
                                }
                                $dataCorrente->modify('+1 day');
                            }
                        }
                    }
                }
            }
 
                // Adiciona cada data calculada como um evento separado
                foreach ($datasAplicacao as $data) {
                    $eventos[] = [
                        'nome' => 'Medicamento: ' . $medicamento['nome'] . ' : ' . (new DateTime($data))->format('H:i'),
                        'data_evento' => $data,
                        'tipo_evento' => 'medicamento'
                    ];
                }
                log_error('datasAplicacao: ' . print_r($datasAplicacao, true));

            }
            if (!empty($eventos)) {
                echo json_encode(['success' => true, 'data' => $eventos]);
            } else {
                // Se não houver eventos calculados, retorna sucesso com dados vazios
                echo json_encode(['success' => true, 'data' => []]);
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao buscar eventos: ' . $e->getMessage()]);
        }
    }
}