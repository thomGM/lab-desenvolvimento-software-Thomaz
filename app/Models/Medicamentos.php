<?php

class Medicamentos {
    private $conexao;
    public $id;
    public $id_ficha;
    public $nome;
    public $dosagem;
    public $viaAdministracao;
    public $inicioTratamento;
    public $fimTratamento;
    public $ultima_aplicacao; 
    public $repetir;
    public $intervalo;
    public $diasMedicamento;
    public $horasMedicamento;

    public function __construct() {
        $this->conexao = conexao();
    }

    public function listarPorCliente($idCliente) {
        $stmt = $this->conexao->prepare("SELECT * FROM Medicamento WHERE id_ficha = :id_ficha");
        $stmt->execute(['id_ficha' => $idCliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar() {
        $stmt = $this->conexao->prepare("INSERT INTO Medicamento (id_ficha, nome, dosagem, viaAdministracao, inicioTratamento, fimTratamento, ultima_aplicacao, repetir, intervalo, diasMedicamento, horasMedicamento) VALUES (:id_ficha, :nome, :dosagem, :viaAdministracao, :inicioTratamento, :fimTratamento, :ultima_aplicacao, :repetir, :intervalo, :diasMedicamento, :horasMedicamento)");
        $stmt->execute([
            'id_ficha' => $this->id_ficha,
            'nome' => $this->nome,
            'dosagem' => $this->dosagem,
            'viaAdministracao' => $this->viaAdministracao,
            'inicioTratamento' => $this->inicioTratamento,
            'fimTratamento' => $this->fimTratamento,
            'ultima_aplicacao' => $this->ultima_aplicacao,
            'repetir' => $this->repetir,
            'intervalo' => $this->intervalo,
            'diasMedicamento' => $this->diasMedicamento,
            'horasMedicamento' => $this->horasMedicamento
        ]);
        return $this->conexao->lastInsertId();
    }

    public function atualizar() {
        $stmt = $this->conexao->prepare("UPDATE Medicamento SET id_ficha = :id_ficha, nome = :nome, dosagem = :dosagem, viaAdministracao = :viaAdministracao, inicioTratamento = :inicioTratamento, fimTratamento = :fimTratamento, ultima_aplicacao = :ultima_aplicacao, repetir = :repetir, intervalo = :intervalo, diasMedicamento = :diasMedicamento, horasMedicamento = :horasMedicamento WHERE id = :id");
        $stmt->execute([
            'id_ficha' => $this->id_ficha,
            'nome' => $this->nome,
            'dosagem' => $this->dosagem,
            'viaAdministracao' => $this->viaAdministracao,
            'inicioTratamento' => $this->inicioTratamento,
            'fimTratamento' => $this->fimTratamento,
            'ultima_aplicacao' => $this->ultima_aplicacao,
            'repetir' => $this->repetir,
            'intervalo' => $this->intervalo,
            'diasMedicamento' => $this->diasMedicamento,
            'horasMedicamento' => $this->horasMedicamento,
            'id' => $this->id
        ]);
        return ;
    }

    public function deletar($id_ficha) {
        $stmt = $this->conexao->prepare("DELETE FROM Medicamento WHERE id_ficha = :id_ficha");
        $stmt->execute(['id_ficha' => $id_ficha]);
        return ;
    }
}
