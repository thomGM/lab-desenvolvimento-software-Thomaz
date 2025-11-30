<?php

class Procedimento {
    private $conexao;
    public $id;
    public $nome;
    public $status;
   
    public function __construct() {
        $this->conexao = conexao();
    }

    public function consulta() {
        $stmt = $this->conexao->prepare("SELECT * FROM procedimento where status = 1");
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
        $stmt = $this->conexao->prepare("UPDATE procedimento SET status = :statusP WHERE id = :id");
        $stmt->execute(['statusP' => $this->status, 'id' => $this->id]);
        return $stmt->rowCount() > 0;
    }

    public function listarPorData($id_ficha, $data) {
        require_once __DIR__ . '/../Core/log.php';
        
        log_error('Procedimento.listarPorData - id_ficha: ' . $id_ficha . ', data: ' . $data);
        
        $stmt = $this->conexao->prepare("
            SELECT p.*, pe.horarios, pe.descricao 
            FROM procedimento p 
            INNER JOIN ProcedimentoEspecifico pe ON p.id = pe.id_procedimento 
            WHERE pe.id_ficha = :id_ficha");
        
        $stmt->execute(['id_ficha' => $id_ficha]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        log_error('Procedimentos encontrados: ' . count($result));
        
        return $result;
    }
}