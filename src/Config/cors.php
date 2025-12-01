<?php

namespace App\Config;

// Define o cabeçalho para retornar dados em JSON
header('Content-Type: application/json');

// Permite requisições de qualquer origem (CORS - Básico para testes)
header("Access-Control-Allow-Origin: *");

// Permite os métodos HTTP que serão usados (GET, POST, PUT, DELETE)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Permite os cabeçalhos que serão enviados pelo cliente
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Lida com o "preflight request" do CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
