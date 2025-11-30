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
    public function buscarPorId($id) {
        $stmt = $this->conexao->prepare("SELECT * FROM medicos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function alterar() {
        $stmt = $this->conexao->prepare("UPDATE medicos SET nome = :nome, crm = :crm, especialidade = :especialidade, telefone = :telefone WHERE id = :id");
        return $stmt->execute([
            'nome' => $this->nome,
            'crm' => $this->crm,
            'especialidade' => $this->especialidade,
            'telefone' => $this->telefone,
            'id' => $this->id
        ]);
    }
}
?>
