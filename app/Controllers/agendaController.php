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
    
                    if ($medicamento['repetir'] == 1) { // Todos os dias
                        log_error('Repetir diariamente');
                        if ($medicamento['intervalo'] ==  1) { // Intervalo em horas
                               $intervaloHoras = (int)$medicamento['horasMedicamento'];
                                if ($intervaloHoras > 0) {
                                    $proximaAplicacao = clone $ultimaAplicacao; 
                                    $proximaAplicacao->modify("+{$intervaloHoras} hours"); 
                                    $datasAplicacao[] = $proximaAplicacao->format('Y-m-d H:i:s');
                                }
    
                            } else if ($medicamento['intervalo'] == 2) { // Intervalo em minutos
                               $intervaloMinutos = (int)$medicamento['horasMedicamento']; // O campo é o mesmo
                                if ($intervaloMinutos > 0) {
                                    $proximaAplicacao = clone $ultimaAplicacao; 
                                    $proximaAplicacao->modify("+{$intervaloMinutos} minutes"); 
                                    $datasAplicacao[] = $proximaAplicacao->format('Y-m-d H:i:s');
                                }
    
                            } else if ($medicamento['intervalo'] == 3) { // Horas específicas
                                log_error('Repetir em horas específicas');
                                $horarios = explode(',', $medicamento['horasMedicamento']); 
                                log_error('Horarios: ' . json_encode($horarios));
                                foreach ($horarios as $horario) {
                                    $horario = trim($horario); 
                                    if (!empty($horario)) {
                                        $hora = (int)substr($horario, 0, 2);
                                        $minuto = (int)substr($horario, 3, 2);
                                        
                                        $dataEspecifica = clone $ultimaAplicacao;
                                        $dataEspecifica->setTime($hora, $minuto, 0); 
                                        
                                        $datasAplicacao[] = $dataEspecifica->format('Y-m-d H:i:s');
                                    }
                                }
                            }
                        }
                }
 
                // Adiciona cada data calculada como um evento separado
                foreach ($datasAplicacao as $data) {
                    $eventos[] = [
                        'nome' => $medicamento['nome'],
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