<?php

class Medicamentos {
    private $conexao;
    public $id;
    public $id_ficha;
    public $nome;
    public $dosagem;
    public $frequencia;
    public $viaAdministracao;
    public $inicioTratamento;
    public $fimTratamento;
    public $ultima_aplicacao; 

    public function __construct() {
        $this->conexao = conexao();
    }

    public function listarPorCliente($idCliente) {
        $stmt = $this->conexao->prepare("SELECT * FROM Medicamento WHERE id_ficha = :id_ficha");
        $stmt->execute(['id_ficha' => $idCliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar() {
        $stmt = $this->conexao->prepare("INSERT INTO Medicamento (id_ficha, nome, dosagem, frequencia, viaAdministracao, inicioTratamento, fimTratamento, ultima_aplicacao) VALUES (:id_ficha, :nome, :dosagem, :frequencia, :viaAdministracao, :inicioTratamento, :fimTratamento, :ultima_aplicacao)");
        $stmt->execute([
            'id_ficha' => $this->id_ficha,
            'nome' => $this->nome,
            'dosagem' => $this->dosagem,
            'frequencia' => $this->frequencia,
            'viaAdministracao' => $this->viaAdministracao,
            'inicioTratamento' => $this->inicioTratamento,
            'fimTratamento' => $this->fimTratamento,
            'ultima_aplicacao' => $this->ultima_aplicacao
        ]);
        return $this->conexao->lastInsertId();
    }
}
