<?php

require_once 'conexao.php'; 

function load($classe) {
    $baseDir = dirname(__DIR__);
    $pastas = [
        'Controllers',
        'Models',
        'Core'
    ];

    foreach ($pastas as $pasta) {
        $arquivo = $baseDir . '/' . $pasta . '/' . $classe . '.php';

        if (file_exists($arquivo)) {
            require_once $arquivo;
            return; // Encontrou e carregou
        }
    }
}

spl_autoload_register("load");
?>