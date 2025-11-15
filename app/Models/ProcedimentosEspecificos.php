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
    public function atualizar() {
        $stmt = $this->conexao->prepare("UPDATE ProcedimentoEspecifico SET id_ficha = :id_ficha, id_procedimento = :id_procedimento, horarios = :horarios, descricao = :descricao WHERE id = :id");
        $stmt->execute([
            'id_ficha' => $this->id_ficha,
            'id_procedimento' => $this->id_procedimento,
            'horarios' => $this->horarios,
            'descricao' => $this->descricao,
            'id' => $this->id
        ]);
        return ;
    }
    public function deletar($id_ficha) {
        $stmt = $this->conexao->prepare("DELETE FROM ProcedimentoEspecifico WHERE id_ficha = :id_ficha");
        $stmt->execute(['id_ficha' => $id_ficha]);
        return ;
    }
}
