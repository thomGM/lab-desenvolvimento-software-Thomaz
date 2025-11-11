<?php

class Clientes {
    private $conexao;
    public $id;
    public $nome;
    public $dataNascimento;
    public $endereco;
    public $telefone;
    public $telefoneEmergencia;

    public function __construct() {
        $this->conexao = conexao();
    }

    public function listarTodos() {
        $stmt = $this->conexao->prepare("SELECT * FROM paciente");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function buscar($nome) {
        $stmt = $this->conexao->prepare("SELECT * FROM paciente WHERE nome LIKE :nome");
        $stmt->execute(['nome' => "%$nome%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar() {
        $stmt = $this->conexao->prepare("INSERT INTO paciente (nome, dataNascimento, endereco, telefone, telefoneEmergencia) VALUES (:nome, :dataNascimento, :endereco, :telefone, :telefoneEmergencia)");
        $stmt->execute([
            'nome' => $this->nome,
            'dataNascimento' => $this->dataNascimento,
            'endereco' => $this->endereco,
            'telefone' => $this->telefone,
            'telefoneEmergencia' => $this->telefoneEmergencia
        ]);
        $this->setId($this->conexao->lastInsertId());
        return;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
}
