<?php

use App\Config\Auth;
use App\Config\Router;
use App\Controllers\v1\Apartamento\ApartamentoController;
use App\Controllers\v1\Balance\CaixaController;
use App\Controllers\v1\Customer\ClienteController;
use App\Controllers\v1\Dashboard\DashboardController;
use App\Controllers\v1\Payment\PagamentoController;
use App\Controllers\v1\Permission\PermissaoController;
use App\Controllers\v1\Product\ProdutoController;
use App\Controllers\v1\Profile\UsuarioController;
use App\Controllers\v1\Reservate\ConsumoController;
use App\Controllers\v1\Reservate\DiariaController;
use App\Controllers\v1\Reservate\ReservaController;
use App\Controllers\v1\Sale\ItemVendaController;
use App\Controllers\v1\Sale\VendaController;

$router = new Router();
$auth = new Auth();
$dashboardController = new DashboardController();
$apartamentoController = new ApartamentoController();
$usuarioController = new UsuarioController(); 
$permissaoController = new PermissaoController();
$clienteController = new ClienteController();
$reservaController = new ReservaController();
$diariaController = new DiariaController();
$consumoController = new ConsumoController();
$produtoController = new ProdutoController();
$pagamentoController = new PagamentoController();
$vendaController = new VendaController();
$itemVendaController = new ItemVendaController();

$caixaController = new CaixaController();

$router->create('GET', '/dashboard', [$dashboardController, 'index'], $auth);
$router->create('GET', '/dashboard/facility', [$dashboardController, 'indexFacility'], $auth);

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

$router->create('POST', '/reserva/{id}/deletar', [$reservaController, 'delete'], $auth);
$router->create('GET', '/reserva/{id}/editar', [$reservaController, 'edit'], $auth);
$router->create('POST', '/reserva/{id}/upt', [$reservaController, 'update'], $auth);
$router->create('POST', '/reserva/add/', [$reservaController, 'store'], $auth);
$router->create('GET', '/reserva/criar', [$reservaController, 'create'], $auth);
$router->create('GET', '/reserva/group', [$reservaController, 'createGroup'], $auth);
$router->create('GET', '/reserva/{request}', [$reservaController, 'index'], $auth);
$router->create('GET', '/reserva/', [$reservaController, 'index'], $auth);
$router->create('POST', '/reserva/apartamentos', [$reservaController, 'findAvailableApartments'], $auth);
$router->create('GET', '/checkin/reserva', [$reservaController, 'checkin'], $auth);
$router->create('POST', '/checkin/{id}/reserva', [$reservaController, 'executeCkeckin'], $auth);
$router->create('GET', '/reserva/checkout', [$reservaController, 'checkout'], $auth);
$router->create('GET', '/maps', [$reservaController, 'maps'], $auth);
$router->create('POST', '/maps', [$reservaController, 'reserve_by_maps'], $auth);

$router->create('GET', '/reservas/{token}/diarias/atualizar',[$diariaController, 'generateDaily']);

$router->create('GET', '/consumos/diaria', [$diariaController, 'index'], $auth);
$router->create('GET', '/consumos/reserva/{id}/diarias', [$diariaController, 'indexJsonByReservaUuid'], $auth);
$router->create('POST', '/consumos/reserva/{id}/diarias', [$diariaController, 'storeByJson'], $auth);
$router->create('GET', '/consumos/reserva/{id}/diarias/{diaria_id}', [$diariaController, 'showByJson'], $auth);
$router->create('POST', '/consumos/reserva/{id}/diarias/{diaria_id}', [$diariaController, 'updateByJson'], $auth);
$router->create('DELETE', '/consumos/reserva/{id}/diarias/{diaria_id}', [$diariaController, 'destroy'], $auth);
$router->create('DELETE', '/consumos/reserva/{id}/diarias', [$diariaController, 'destroyAll'], $auth);

$router->create('GET', '/consumos/produto', [$consumoController, 'index'], $auth);
$router->create('GET', '/consumos/reserva/{id}/produto', [$consumoController, 'indexJsonByReservaUuid'], $auth);
$router->create('POST', '/consumos/reserva/{id}/produto', [$consumoController, 'storeByJson'], $auth);
$router->create('GET', '/consumos/reserva/{id}/produto/{consumo_id}', [$consumoController, 'showByJson'], $auth);
$router->create('POST', '/consumos/reserva/{id}/produto/{consumo_id}', [$consumoController, 'updateByJson'], $auth);
$router->create('DELETE', '/consumos/reserva/{id}/produto/{consumo_id}', [$consumoController, 'destroy'], $auth);
$router->create('DELETE', '/consumos/reserva/{id}/produto', [$consumoController, 'destroyAll'], $auth);

$router->create('GET', '/consumos/pagamento', [$pagamentoController, 'index'], $auth);
$router->create('GET', '/consumos/reserva/{id}/pagamento', [$pagamentoController, 'indexJsonByReservaUuid'], $auth);
$router->create('POST', '/consumos/reserva/{id}/pagamento', [$pagamentoController, 'storeByJson'], $auth);
$router->create('GET', '/consumos/reserva/{id}/pagamento/{pagamento_id}', [$pagamentoController, 'showByJson'], $auth);
$router->create('POST', '/consumos/reserva/{id}/pagamento/{pagamento_id}', [$pagamentoController, 'updateByJson'], $auth);
$router->create('DELETE', '/consumos/reserva/{id}/pagamento/{pagamento_id}', [$pagamentoController, 'destroy'], $auth);
$router->create('DELETE', '/consumos/reserva/{id}/pagamento', [$pagamentoController, 'destroyAll'], $auth);

$router->create('GET', '/vendas', [$vendaController, 'index'], $auth);
// $router->create('GET', '/consumos/venda/list', [$produtoController, 'indexJsonWithoutPaginate'], $auth);
$router->create('GET', '/vendas/criar', [$vendaController, 'create'], $auth);
$router->create('POST', '/vendas', [$vendaController, 'store'], $auth);
$router->create('GET', '/vendas/{id}', [$vendaController, 'edit'], $auth);
$router->create('POST', '/vendas/{id}', [$vendaController, 'update'], $auth);
// $router->create('POST', '/produtos/{produto_id}/remove', [$produtoController, 'destroy'], $auth);

$router->create('GET', '/vendas/{id}/items', [$itemVendaController, 'indexJsonByReservaUuid'], $auth);
$router->create('POST', '/vendas/{id}/items', [$itemVendaController, 'storeByJson'], $auth);
$router->create('GET', '/vendas/{id}/items/{consumo_id}', [$itemVendaController, 'showByJson'], $auth);
$router->create('POST', '/vendas/{id}/items/{consumo_id}', [$itemVendaController, 'updateByJson'], $auth);
$router->create('DELETE', '/vendas/{id}/items/{consumo_id}', [$itemVendaController, 'destroy'], $auth);
$router->create('DELETE', '/vendas/{id}/items', [$itemVendaController, 'destroyAll'], $auth);

$router->create('GET', '/vendas/{id}/pagamento', [$pagamentoController, 'indexJsonWithSaleByUuid'], $auth);
$router->create('POST', '/vendas/{id}/pagamento', [$pagamentoController, 'storeWithSaleByJson'], $auth);
$router->create('GET', '/vendas/{id}/pagamento/{pagamento_id}', [$pagamentoController, 'showWithSaleByJson'], $auth);
$router->create('POST', '/vendas/{id}/pagamento/{pagamento_id}', [$pagamentoController, 'updateWithSaleByJson'], $auth);
$router->create('DELETE', '/vendas/{id}/pagamento/{pagamento_id}', [$pagamentoController, 'destroyWithSale'], $auth);
$router->create('DELETE', '/vendas/{id}/pagamento', [$pagamentoController, 'destroyWithSaleAll'], $auth);

$router->create('GET', '/produtos', [$produtoController, 'index'], $auth);
$router->create('GET', '/produtos/list', [$produtoController, 'indexJsonWithoutPaginate'], $auth);
$router->create('GET', '/produtos/criar', [$produtoController, 'create'], $auth);
$router->create('POST', '/produtos', [$produtoController, 'store'], $auth);
$router->create('GET', '/produtos/{produto_id}', [$produtoController, 'edit'], $auth);
$router->create('POST', '/produtos/{produto_id}', [$produtoController, 'update'], $auth);
$router->create('POST', '/produtos/{produto_id}/remove', [$produtoController, 'destroy'], $auth);
// $router->create('DELETE', '/produtos', [$produtoController, 'destroyAll'], $auth);

$router->create('POST', '/caixa/create', [$caixaController, 'storeByJson'], $auth);

$router->create('GET', '/', [$usuarioController, 'login'], null);
$router->create('POST', '/login', [$usuarioController, 'auth']);
$router->create('GET', '/logout', [$usuarioController, 'logout'], $auth);
