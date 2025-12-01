<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Services\PessoaService;

class ApiController
{

    private PessoaService $pessoaService;

    public function __construct()
    {
        $this->pessoaService = new PessoaService();
    }

    /**
     * Cria uma nova pessoa no banco de dados (POST).
     */
    public function create(Request $request, Response $response)
    {
        $body = $request::body();
        $result = $this->pessoaService->create($body);

        // Retorna mensagem de sucesso se a operação funcionar.
        if (isset($result['success']) && $result['success'] === true) {
            return $response::json($result, 200);
        }

        // Caso contrário, retorna erro.
        else {
            return $response::json($result, 400);
        }
    }

    /**
     * Retorna todas as pessoas cadastradas (GET).
     */
    function getAll(Request $request, Response $response, $matches)
    {
        $pessoas = $this->pessoaService->getAll();
        return $response::json($pessoas);
    }

    /**
     * Atualiza uma pessoa existente (PUT).
     */
    function update(Request $request, Response $response, $matches)
    {
        $body = $request::body();
        $urlId = $matches[0];

        $result = $this->pessoaService->update($urlId, $body);
        return $response::json($result);
    }

    /**
     * Deleta uma pessoa existente (DELETE).
     */
    function delete(Request $request, Response $response, $matches)
    {
        $urlId = $matches[0];

        $result = $this->pessoaService->delete($urlId);
        return $response::json($result);
    }
}
