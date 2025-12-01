<?php

namespace App\Core;

use App\Http\Request;
use App\Http\Response;

class Core
{
    public static function dispatch(array $routes)
    {
        $url = $_SERVER['REQUEST_URI'];
        $url !== '/' && $url = rtrim($url, '/');
        $prefixController = 'App\\Controllers\\';
        $routeFound = false;

        foreach ($routes as $route) {
            // Substitui {id} de acordo com o valor da URL.
            $pattern = '#^' . preg_replace('/{id}/', '([\w-]+)', $route['path']) . '$#';

            // Confere se a url providenciada contem algum parâmetro dinâmico (e.g. /api/{id}).
            $matches = [];
            $hasDynamicSeg = preg_match($pattern, $url, $matches);

            if ($hasDynamicSeg) {
                array_shift($matches);
                $path = isset($matches[0]) ? preg_replace('/{id}/', $matches[0], $route['path']) : $route['path'];
                $routeFound = true;

                // Chama o controller e função definidos para a rota no arquivo main.php caso o path e o method sejam iguais.
                if ($path === $url && $route['method'] === Request::method()) {
                    [$controller, $action] = explode('@', $route['action']);
                    $controller = $prefixController . $controller;
                    $extendController = new $controller();
                    $extendController->$action(new Request, new Response, $matches);
                    break;
                }

                // Responde com um erro se o método HTTP não é permitido pela rota.
                else if ($path !== $url && $route['method'] !== Request::method()) {
                    Response::json([
                        'success' => false,
                        'message' => 'Method not allowed.'
                    ], 405);
                } else continue;
            }
        }

        if (!$routeFound) {
            Response::json([
                'success' => false,
                'message' => 'Route not found.'
            ], 404);
        }
    }
}
