<?php

class Clientes {
    private $conexao;
    public $id;
    public $nome;
    public $dataNascimento;
    public $endereco;
    public $telefone;
    public $telefoneEmergencia;
    public $cpf;

    public function __construct() {
        $this->conexao = conexao();
    }

    public function listarTodos() {
        $stmt = $this->conexao->prepare("SELECT * FROM paciente");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function buscar($nome, $tipo) {
        if ($tipo == 'id') {
            $stmt = $this->conexao->prepare("SELECT * FROM paciente WHERE $tipo = :nome");
            $stmt->execute(['nome' => $nome]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
             $stmt = $this->conexao->prepare("SELECT * FROM paciente WHERE $tipo LIKE :nome");
            $stmt->execute(['nome' => "%$nome%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function salvar() {
        $stmt = $this->conexao->prepare("INSERT INTO paciente (nome, dataNascimento, endereco, telefone, telefoneEmergencia, cpf) VALUES (:nome, :dataNascimento, :endereco, :telefone, :telefoneEmergencia, :cpf)");
        $stmt->execute([
            'nome' => $this->nome,
            'dataNascimento' => $this->dataNascimento,
            'endereco' => $this->endereco,
            'telefone' => $this->telefone,
            'telefoneEmergencia' => $this->telefoneEmergencia,
            'cpf' => $this->cpf
        ]);
        $this->setId($this->conexao->lastInsertId());
        return;
    }

    public function atualizar() {
        $stmt = $this->conexao->prepare("UPDATE paciente SET nome = :nome, dataNascimento = :dataNascimento, endereco = :endereco, telefone = :telefone, telefoneEmergencia = :telefoneEmergencia, cpf = :cpf WHERE id = :id");
        $stmt->execute([
            'nome' => $this->nome,
            'dataNascimento' => $this->dataNascimento,
            'endereco' => $this->endereco,
            'telefone' => $this->telefone,
            'telefoneEmergencia' => $this->telefoneEmergencia,
            'cpf' => $this->cpf,
            'id' => $this->id
        ]);
        return;
    }

    public function buscarTelefonePorId($id) {
        $stmt = $this->conexao->prepare("SELECT telefoneEmergencia FROM paciente WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
}
