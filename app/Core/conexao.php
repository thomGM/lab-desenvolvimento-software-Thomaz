<?php

function conexao(){
    $dsn = "mysql:host=localhost;dbname=homecare";
    $usuario = "root";
    $senha = "";

    try{
        $conexao = new PDO($dsn, $usuario, $senha);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexao;    
    }catch(Exception $e){
        echo "Erro: " . $e->getMessage();
    }

}