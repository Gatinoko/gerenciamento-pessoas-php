<?php

namespace App\Http;

require_once __DIR__ . "/../Config/config.php";

use mysqli;

class Database
{

    private mysqli $conn;

    public function __construct()
    {
        // Cria a conexão ao banco de dados com as credenciais especificadas no arquivo config.php.
        $this->conn =  new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        // Em caso de erro de conexão, retorna um erro 500.
        if ($this->conn->connect_error) {
            http_response_code(500);
            die(json_encode(
                [
                    'message' => 'Erro de conexão com o banco de dados: ' . $this->conn->connect_error
                ]
            ));
        }
    }

    public function getConnection(): mysqli
    {
        return $this->conn;
    }
}
