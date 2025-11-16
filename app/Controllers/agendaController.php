<?php
require_once __DIR__ . '/../Core/log.php';

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
            log_error('Medicamentos para agenda: ' . json_encode($medicamentos));
            $eventos = [];

            foreach ($medicamentos as $medicamento) {
                log_error('Processando medicamento: ');
                // Calcular as datas de aplicação com base na última aplicação e frequência
                $datasAplicacao = [];
                if (!empty($medicamento['ultima_aplicacao'])) {
                    $ultimaAplicacao = new DateTime($medicamento['ultima_aplicacao']);
                    log_error('Ultima aplicacao: ' . $ultimaAplicacao->format('Y-m-d H:i'));
                    log_error('Repetir: ' . $medicamento['repetir'] . ' Intervalo: ' . $medicamento['intervalo']);
    
                    // Define o limite para o loop
                    $dataFimTratamento = new DateTime($medicamento['fimTratamento']);
                    $proximaAplicacao = clone $ultimaAplicacao;

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
                            $dataCorrente = clone $ultimaAplicacao;
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
                                        if ($dataEspecifica > $ultimaAplicacao && $dataEspecifica <= $dataFimTratamento) {
                                            $datasAplicacao[] = $dataEspecifica->format('Y-m-d H:i:s');
                                        }
                                    }
                                }
                                $dataCorrente->modify('+1 day');
                            }
                        }
                    } else if ($medicamento['repetir'] == 2) { // Dias específicos da semana
                        $diasSemana = explode(',', $medicamento['diasMedicamento']); // Ex: [2, 4, 6] para Seg, Qua, Sex
                        $dataCorrente = clone $ultimaAplicacao;
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
    
                                            if ($dataEspecifica > $ultimaAplicacao && $dataEspecifica <= $dataFimTratamento) {
                                                $datasAplicacao[] = $dataEspecifica->format('Y-m-d H:i:s');
                                            }
                                        }
                                    }
                                } else { // Intervalo em horas ou minutos
                                    // Para dias específicos, o primeiro evento do dia começa no início do dia
                                    $proximaDoDia = clone $dataCorrente;
                                    while($proximaDoDia < (clone $dataCorrente)->modify('+1 day') && $proximaDoDia <= $dataFimTratamento) {
                                        if ($proximaDoDia > $ultimaAplicacao) {
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
                            $dataCorrente = clone $ultimaAplicacao;
                            
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
                                        if ($proximaDoDia > $ultimaAplicacao) {
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