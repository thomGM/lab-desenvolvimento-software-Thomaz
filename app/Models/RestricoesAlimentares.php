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
}
