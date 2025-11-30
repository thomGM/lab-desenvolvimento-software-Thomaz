<?php

class Procedimento {
    private $conexao;
    public $id;
    public $nome;
   
    public function __construct() {
        $this->conexao = conexao();
    }

    public function consulta() {
        $stmt = $this->conexao->prepare("SELECT * FROM procedimento");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function salvar() {
        $stmt = $this->conexao->prepare("INSERT INTO procedimento (nome) VALUES (:nome)");
        $stmt->execute([
            'nome' => $this->nome
        ]);
        return $this->conexao->lastInsertId();
    }
    public function buscarPorId($id) {
        $stmt = $this->conexao->prepare("SELECT * FROM procedimento WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function alterar() {
        $stmt = $this->conexao->prepare("UPDATE procedimento SET nome = :nome WHERE id = :id");
        $stmt->execute([
            'nome' => $this->nome,
            'id' => $this->id
        ]);
        return $stmt->rowCount() > 0;
    }
    public function inativar() {
        $stmt = $this->conexao->prepare("UPDATE procedimento SET status = 0 WHERE id = :id");
        $stmt->execute(['id' => $this->id]);
        return $stmt->rowCount() > 0;
    }
}