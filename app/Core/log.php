<?php
// app/Core/log.php
// Função simples para registrar erros em um arquivo de log

function log_error($message) {
    $logDir = __DIR__ . '/../../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0777, true);
    }
    $logFile = $logDir . '/error.log';
    $date = date('Y-m-d H:i:s');
    $entry = "[$date] $message\n";
    file_put_contents($logFile, $entry, FILE_APPEND);
}
