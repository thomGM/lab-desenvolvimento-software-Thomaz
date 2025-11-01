<?php

require_once 'conexao.php';

function load($classe){
    require_once __DIR__ . "/../model/$classe.php";
}

spl_autoload_register("load");