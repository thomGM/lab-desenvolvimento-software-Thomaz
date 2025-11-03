<?php

class Clientes {
    private $conexao;

    public function __construct() {
        $this->conexao = conexao();
    }

    public function buscar($nome) {
        $stmt = $this->conexao->prepare("SELECT * FROM clientes WHERE nome LIKE :nome");
        $stmt->execute(['nome' => "%$nome%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
