<?php

class FichaTecnica {
    private $conexao;
    public $id;
    public $paciente;
    public $historicoMedico;
    public $medicamentos;
    public $restricoesAlimentares;
    public $procedimentosEspecificos;

    public function __construct() {
        $this->conexao = conexao();
    }

    public function listarPorCliente($idCliente) {
        $stmt = $this->conexao->prepare("SELECT * FROM fichaTecnica WHERE paciente = :paciente");
        $stmt->execute(['paciente' => $idCliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function salvar() {
        $stmt = $this->conexao->prepare("INSERT INTO fichaTecnica (paciente, historicoMedico, medicamentos, restricoesAlimentares, procedimentosEspecificos) VALUES (:paciente, :historicoMedico, :medicamentos, :restricoesAlimentares, :procedimentosEspecificos)");
        $stmt->execute([
            'paciente' => $this->paciente,
            'historicoMedico' => $this->historicoMedico,
            'medicamentos' => $this->medicamentos,
            'restricoesAlimentares' => $this->restricoesAlimentares,
            'procedimentosEspecificos' => $this->procedimentosEspecificos
        ]);
        $this->setId($this->conexao->lastInsertId());
        return ;
    }

    public function atualizar() {
        $stmt = $this->conexao->prepare("UPDATE fichaTecnica SET paciente = :paciente, historicoMedico = :historicoMedico, medicamentos = :medicamentos, restricoesAlimentares = :restricoesAlimentares, procedimentosEspecificos = :procedimentosEspecificos WHERE id = :id");
        $stmt->execute([
            'paciente' => $this->paciente,
            'historicoMedico' => $this->historicoMedico,
            'medicamentos' => $this->medicamentos,
            'restricoesAlimentares' => $this->restricoesAlimentares,
            'procedimentosEspecificos' => $this->procedimentosEspecificos,
            'id' => $this->id
        ]);
        return ;
    }   

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }
}

