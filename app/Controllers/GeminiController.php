<?php
require_once __DIR__ . '/../Core/log.php';
require_once __DIR__ . '/../Core/config.php';
class GeminiController {

    private $conexao;

    public function __construct() {
        $this->conexao = conexao();
    }

    public function enviarRelatorio() {
        header('Content-Type: application/json');
        $data = $_REQUEST['data']?? date('Y-m-d');
        $paciente = $_REQUEST['paciente']?? 1;
        $cuidadora = 'Angélica';

        // Pesquisar na agenda tudo que foi concluido
        $agendaModel = new Agenda();
        $concluidos = $agendaModel->getConcluidos($paciente, $data);

        $procedimentosTexto = "";
        if (!empty($concluidos)) {
            foreach ($concluidos as $item) {
                $procedimentosTexto .= $item['procedimentoNome'] != null ? 'Procedimento ' . $item['procedimentoNome'] : 'Medicamento ' . $item['medicamentoNome'] . ' - data: ' . $item['data'] . ' - horário: ' . $item['hora'];
                $paciente = $item['nomePaciente'];
            }
        } else {
            $procedimentosTexto = "Nenhum procedimento foi realizado neste dia.";
        }

        $corpo = [
            "contents"=> [
                [
                    "parts" =>[
                        [
                            "text"=> "Gere um relatório profissional de cuidados domiciliares com as seguintes informações:
                            
                            Cuidadora: $cuidadora
                            Data: $data
                            Paciente ID: $paciente
                            
                            Procedimentos realizados:
                            $procedimentosTexto
                            
                            O relatório deve ser formatado de forma profissional, incluindo:
                            - Cabeçalho com dados da cuidadora e data
                            - Lista detalhada dos procedimentos com horários
                            - Enviar os procedimentos realizados somente, não pode iventar novos
                            
                            Use um formato claro e organizado."
                        ]
                    ]
                ]
            ]
        ];

      
        $apiKey = GEMINI_API_KEY;
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";

        $header = [
            "Content-Type: application/json",
            "x-goog-api-key: $apiKey"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($corpo));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $response = curl_exec($ch);

        if(curl_errno($ch)){
            echo json_encode(['success' => false, 'message' => 'Erro na requisição']);
        }else{
            $result = json_decode($response, true);
            log_error('gemmini');
            log_error($response);

            if(isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                $final = $result['candidates'][0]['content']['parts'][0]['text'];
                $final = rtrim($final, '```');
                $final = ltrim($final, '```json');
                
                echo json_encode(['success' => true, 'relatorio' => trim($final)]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao processar resposta']);
            }
        }
    }
}