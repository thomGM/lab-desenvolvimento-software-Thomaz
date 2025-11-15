<?php

class RestricoesAlimentares {
    private $conexao;
    public $id;
    public $id_ficha;
    public $descricao;
   
    public function __construct() {
        $this->conexao = conexao();
    }

    public function listarPorCliente($idCliente) {
        $stmt = $this->conexao->prepare("SELECT * FROM RestricaoAlimentar WHERE id_ficha = :id_ficha");
        $stmt->execute(['id_ficha' => $idCliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function salvar() {
        $stmt = $this->conexao->prepare("INSERT INTO RestricaoAlimentar (id_ficha, descricao) VALUES (:id_ficha, :descricao)");
        $stmt->execute([
            'id_ficha' => $this->id_ficha,
            'descricao' => $this->descricao
        ]);
        return $this->conexao->lastInsertId();
    }
    public function atualizar() {
        $stmt = $this->conexao->prepare("UPDATE RestricaoAlimentar SET id_ficha = :id_ficha, descricao = :descricao WHERE id = :id");
        $stmt->execute([
            'id_ficha' => $this->id_ficha,
            'descricao' => $this->descricao,
            'id' => $this->id
        ]);
        return ;
    }

    public function deletar($id_ficha) {
        $stmt = $this->conexao->prepare("DELETE FROM RestricaoAlimentar WHERE id_ficha = :id_ficha");
        $stmt->execute(['id_ficha' => $id_ficha]);
        return ;
    }
}
