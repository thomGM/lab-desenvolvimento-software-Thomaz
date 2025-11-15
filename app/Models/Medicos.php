<?php

class Medicos {
    private $conexao;
    public $id;
    public $nome;
    public $crm;
    public $especialidade;
    public $telefone;
   
    public function __construct() {
        $this->conexao = conexao();
    }

    public function consulta() {
        $stmt = $this->conexao->prepare("SELECT * FROM medicos");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function salvar() {
        $stmt = $this->conexao->prepare("INSERT INTO medicos (nome, crm, especialidade, telefone) VALUES (:nome, :crm, :especialidade, :telefone)");
        $stmt->execute([
            'nome' => $this->nome,
            'crm' => $this->crm,
            'especialidade' => $this->especialidade,
            'telefone' => $this->telefone
        ]);
        return $this->conexao->lastInsertId();
    }
}
?>
