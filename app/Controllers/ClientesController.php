<?php
// 1. Importar o Model necessário
require_once '../models/Usuario.php';
// (Aqui também precisaria da conexão com o banco, que viria de um config)

class UsuariosController {

    private $conexao;

    public function __construct() {
        $this->conexao = conexao();
    }

    /**
     * AÇÃO: Listar todos os usuários.
     * Esta função será chamada quando o usuário
     * acessar algo como: index.php?controlador=Usuarios&acao=listar
     */
    public function listar() {
        
        // 2. Chamar o Model para buscar os dados
        $usuarioModel = new Clientes($this->conexao);
        $listaDeUsuarios = $usuarioModel->buscarTodos(); // O Model faz o SQL

        // 3. Chamar a View e passar os dados para ela
        // A View 'lista.php' vai ter acesso à variável $listaDeUsuarios
        // A View é quem vai ter o HTML (o <table>, <ul>, etc.)
        require_once '../views/usuarios/lista.php';
    }

    /**
     * AÇÃO: Ver um usuário específico.
     * Ex: index.php?controlador=Usuarios&acao=ver&id=5
     */
    public function ver() {
        // 1. Validar a entrada
        $id = $_GET['id'] ?? 0;
        if (!is_numeric($id) || $id <= 0) {
            echo "ID inválido!";
            return;
        }

        // 2. Chamar o Model
        $usuarioModel = new Clientes($this->conexao);
        $usuario = $usuarioModel->buscarPorId($id);

        // 3. Chamar a View
        require_once '../views/usuarios/detalhe.php';
    }
}