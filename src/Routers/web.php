<?php

use App\Config\Router;
use App\Controllers\v1\Apartamento\ApartamentoController;
use App\Controllers\v1\Dashboard\DashboardController;

$router = new Router();
$dashboardController = new DashboardController();
$apartamentoController = new ApartamentoController();

$router->create('GET', '/', [$dashboardController, 'index']);

// routes apartamentos
$router->create('POST', '/apartamento/{id}/deletar', [$apartamentoController, 'delete']);
$router->create('GET', '/apartamento/{id}/editar', [$apartamentoController, 'edit']);
$router->create('POST', '/apartamento/{id}/upt', [$apartamentoController, 'update']);
$router->create('POST', '/apartamento/add', [$apartamentoController, 'store']);
$router->create('GET', '/apartamento/criar', [$apartamentoController, 'create']);
$router->create('GET', '/apartamento/{request}', [$apartamentoController, 'index']);
$router->create('GET', '/apartamento/', [$apartamentoController, 'index']);

return $router;
