<?php

class Agenda {
    private $conexao;
    public $id;
    public $data;
    public $atividade;
    public $paciente;
    public $hora;
    public $tipo;
    public $status;

    public $horaAgendada;
   
    public function __construct() {
        $this->conexao = conexao();
    }

    public function marcarConcluida($paciente, $eventoId, $eventoTipo, $hora) {
        $stmt = $this->conexao->prepare("
            INSERT INTO agenda (paciente, atividade, data, hora, tipo, status, horaAgendada) 
            VALUES (:paciente, :atividade, :data, :hora, :tipo, 1, :horaAgendada)
            ON DUPLICATE KEY UPDATE status = 1");
        
        $stmt->execute([
            'paciente' => $paciente,
            'atividade' => $eventoId,
            'data' => date('Y-m-d'),
            'hora' => date('H:i'),
            'tipo' => $eventoTipo,
            'horaAgendada' => $hora
        ]);
    }

    public function desmarcarConcluida($paciente, $eventoId, $eventoTipo) {
        $stmt = $this->conexao->prepare("
            UPDATE agenda SET status = 0 
            WHERE paciente = :paciente AND atividade = :atividade 
            AND tipo = :tipo AND data = :data");
        
        $stmt->execute([
            'paciente' => $paciente,
            'atividade' => $eventoId,
            'tipo' => $eventoTipo,
            'data' => date('Y-m-d'),
        ]);
    }

    public function verificarConcluidas($paciente, $data) {
        $stmt = $this->conexao->prepare("
            SELECT atividade, tipo, horaAgendada FROM agenda 
            WHERE paciente = :paciente AND data = :data AND status = 1");
        
        $stmt->execute(['paciente' => $paciente, 'data' => $data]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $concluidas = [];
        foreach ($result as $row) {
            $concluidas[] = [
                'evento_id' => $row['atividade'],
                'evento_tipo' => $row['tipo'],
                'horaAgendada' => $row['horaAgendada']
            ];
        }
        
        return $concluidas;
    }

    public function getConcluidos($data, $paciente) {
         $stmt = $this->conexao->prepare("
            SELECT a.hora, a.data, p.nome as procedimentoNome, m.nome as medicamentoNome, pa.nome as nomePaciente FROM agenda a
            inner join paciente pa on pa.id = a.paciente
            left join medicamento m on m.id = a.atividade and a.tipo = 'm'
            left join Procedimento p on p.id = a.atividade and a.tipo = 'p'
            WHERE a.paciente = :paciente AND a.data = :data AND a.status = 1");

            $stmt->execute(['paciente' => $paciente, 'data' => $data]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
    }
}
