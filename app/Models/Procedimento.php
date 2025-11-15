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
}
