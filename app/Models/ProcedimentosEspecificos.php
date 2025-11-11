<?php

class ProcedimentosEspecificos {
    private $conexao;
    public $id;
    public $id_ficha;
    public $id_procedimento;
    public $horarios;
    public $descricao;
   
    public function __construct() {
        $this->conexao = conexao();
    }

    public function listarPorCliente($idCliente) {
        $stmt = $this->conexao->prepare("SELECT * FROM ProcedimentoEspecifico WHERE id_ficha = :id_ficha");
        $stmt->execute(['id_ficha' => $idCliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function salvar() {
        $stmt = $this->conexao->prepare("INSERT INTO ProcedimentoEspecifico (id_ficha, id_procedimento, horarios, descricao) VALUES (:id_ficha, :id_procedimento, :horarios, :descricao)");
        $stmt->execute([
            'id_ficha' => $this->id_ficha,
            'id_procedimento' => $this->id_procedimento,
            'horarios' => $this->horarios,
            'descricao' => $this->descricao
        ]);
        return $this->conexao->lastInsertId();
    }
}
