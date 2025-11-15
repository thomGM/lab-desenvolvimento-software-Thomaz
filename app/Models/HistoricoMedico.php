<?php

class HistoricoMedico {
    private $conexao;
    public $id;
    public $id_ficha;
    public $descricao;
    public $id_medico;
   
    public function __construct() {
        $this->conexao = conexao();
    }

    public function listarPorCliente($idCliente) {
        $stmt = $this->conexao->prepare("SELECT * FROM historicoMedico WHERE id_ficha = :id_ficha");
        $stmt->execute(['id_ficha' => $idCliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function salvar() {
        $stmt = $this->conexao->prepare("INSERT INTO historicoMedico (id_ficha, descricao, id_medico) VALUES (:id_ficha, :descricao, :id_medico)");
        $stmt->execute([
            'id_ficha' => $this->id_ficha,
            'descricao' => $this->descricao,
            'id_medico' => $this->id_medico
        ]);
        return $this->conexao->lastInsertId();
    }
    public function atualizar() {
        $stmt = $this->conexao->prepare("UPDATE historicoMedico SET id_ficha = :id_ficha, descricao = :descricao, id_medico = :id_medico WHERE id = :id");
        $stmt->execute([
            'id_ficha' => $this->id_ficha,
            'descricao' => $this->descricao,
            'id_medico' => $this->id_medico,
            'id' => $this->id
        ]);
        return ;
    }

    public function deletar($id_ficha) {
        $stmt = $this->conexao->prepare("DELETE FROM historicoMedico WHERE id_ficha = :id_ficha");
        $stmt->execute(['id_ficha' => $id_ficha]);
        return ;
    }
}
