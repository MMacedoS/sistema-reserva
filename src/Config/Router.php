<?php

namespace App\Config;

use App\Request\Request;

class Router {
    protected $routers = [];

    public function create(
        string $method, 
        string $path, 
        callable $callback 
      ) 
      {
        $this->routers[$method][$path] = $callback;
      }

      public function init() 
      {    
        $httpMethod = $_SERVER["REQUEST_METHOD"];
        $requestUri = $_SERVER["REQUEST_URI"];
        $request = new Request();
    
        // O método atual existe em nossas rotas?
        if (isset($this->routers[$httpMethod])) {
    
          // Percore as rotas com o método atual:
          foreach (
            $this->routers[$httpMethod] as $path => $callback
          ) {

            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $path);
            $pattern = str_replace('/', '\/', $pattern);
            
            if (preg_match('/^' . $pattern . '$/', $requestUri, $matches)) {
              array_shift($matches); // Remove o primeiro elemento, que é a string completa
              return $callback($request,...$matches);
            }
          }
        }
    
        // Caso não exista a rota/método atual: 
        http_response_code(404);
        return;
      }

      public function view(string $viewName, array $data = []) {
        extract($data);
        require_once __DIR__ . '/../Resources/Views/' . $viewName . '.php';
    }

    public function redirect($page) {
      return header('Location: ' . URL_PREFIX . $page);
    }
    
}