<?php

use App\Config\Auth;
use App\Config\Router;
use App\Controllers\v1\Apartamento\ApartamentoController;
use App\Controllers\v1\Customer\ClienteController;
use App\Controllers\v1\Dashboard\DashboardController;
use App\Controllers\v1\Permission\PermissaoController;
use App\Controllers\v1\Profile\UsuarioController;

$router = new Router();
$auth = new Auth();
$dashboardController = new DashboardController();
$apartamentoController = new ApartamentoController();
$usuarioController = new UsuarioController(); 
$permissaoController = new PermissaoController();
$clienteController = new ClienteController();

$router->create('GET', '/dashboard', [$dashboardController, 'index'], $auth);

// routes apartamentos
$router->create('POST', '/apartamento/{id}/deletar', [$apartamentoController, 'delete'], $auth);
$router->create('GET', '/apartamento/{id}/editar', [$apartamentoController, 'edit'], $auth);
$router->create('POST', '/apartamento/{id}/upt', [$apartamentoController, 'update'], $auth);
$router->create('POST', '/apartamento/add', [$apartamentoController, 'store'], $auth);
$router->create('GET', '/apartamento/criar', [$apartamentoController, 'create'], $auth);
$router->create('GET', '/apartamento/{request}', [$apartamentoController, 'index'], $auth);
$router->create('GET', '/apartamento', [$apartamentoController, 'index'], $auth);

//users
$router->create('POST', '/usuario/{id}/deletar', [$usuarioController, 'delete'], $auth);
$router->create('GET', '/usuario/{id}/editar', [$usuarioController, 'edit'], $auth);
$router->create('POST', '/usuario/{id}/upt', [$usuarioController, 'update'], $auth);
$router->create('GET', '/usuario/{id}/permissao', [$usuarioController, 'permissao'], $auth);
$router->create('POST', '/usuario/{id}/permissao', [$usuarioController, 'add_permissao'], $auth);
$router->create('POST', '/usuario/add/', [$usuarioController, 'store'], $auth);
$router->create('GET', '/usuario/criar', [$usuarioController, 'create'], $auth);
$router->create('GET', '/usuario/{request}', [$usuarioController, 'index'], $auth);
$router->create('GET', '/usuario/', [$usuarioController, 'index'], $auth);

$router->create('POST', '/permissao/{id}/deletar', [$permissaoController, 'delete'], $auth);
$router->create('GET', '/permissao/{id}/editar', [$permissaoController, 'edit'], $auth);
$router->create('POST', '/permissao/{id}/upt', [$permissaoController, 'update'], $auth);
$router->create('POST', '/permissao/add/', [$permissaoController, 'store'], $auth);
$router->create('GET', '/permissao/criar', [$permissaoController, 'create'], $auth);
$router->create('GET', '/permissao/{request}', [$permissaoController, 'index'], $auth);
$router->create('GET', '/permissao/', [$permissaoController, 'index'], $auth);

$router->create('POST', '/cliente/{id}/deletar', [$clienteController, 'delete'], $auth);
$router->create('GET', '/cliente/{id}/editar', [$clienteController, 'edit'], $auth);
$router->create('POST', '/cliente/{id}/upt', [$clienteController, 'update'], $auth);
$router->create('POST', '/cliente/add/', [$clienteController, 'store'], $auth);
$router->create('GET', '/cliente/criar', [$clienteController, 'create'], $auth);
$router->create('GET', '/cliente/{request}', [$clienteController, 'index'], $auth);
$router->create('GET', '/cliente/', [$clienteController, 'index'], $auth);

$router->create('GET', '/', [$usuarioController, 'login'], null);
$router->create('POST', '/login', [$usuarioController, 'auth']);
$router->create('GET', '/logout', [$usuarioController, 'logout'], $auth);
