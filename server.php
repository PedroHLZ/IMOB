<?php
// server.php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Caminho absoluto para a pasta "public"
$publicDir = __DIR__ . '/public';

// Se o arquivo físico existir, serve ele normalmente
if ($uri !== '/' && file_exists($publicDir . $uri)) {
    return false;
}

// Caso contrário, carrega o index.php
require_once $publicDir . '/index.php';
