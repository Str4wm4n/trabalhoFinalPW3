<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', function() {
    return "API de Pedidos Funcionando";
});

$routes->group('api', function($routes) {
    $routes->get('status', 'Api\ApiController::api_status');
    $routes->get('produtos', 'Api\ApiController::get_produtos');
    $routes->post('produtos', 'Api\ApiController::criar_produto');
    $routes->put('produtos/(:num)', 'Api\ApiController::atualizar_produto/$1');
    $routes->post('checkout', 'Api\ApiController::checkout');
    
    // Novas rotas para a Cozinha
    $routes->get('pedidos', 'Api\ApiController::get_pedidos');
    $routes->get('pedidos/(:num)', 'Api\ApiController::get_pedido_detalhes/$1');
    $routes->put('pedidos/(:num)', 'Api\ApiController::atualizar_status/$1');

    // Rotas de autenticação
    $routes->post('login', 'Api\AuthController::login');
    $routes->post('verificar-token', 'Api\AuthController::verificarToken');

    // Rotas de usuários (Super Admin e autoedição)
    $routes->get('usuarios', 'Api\UserController::list');
    $routes->post('usuarios', 'Api\UserController::create');
    $routes->get('usuarios/(:num)', 'Api\UserController::get/$1');
    $routes->put('usuarios/(:num)', 'Api\UserController::update/$1');

    // Rotas de relatórios
    $routes->get('relatorios/vendas', 'Api\RelatorioController::vendas');
    $routes->get('relatorios/consumo', 'Api\RelatorioController::consumo');
});

// Rotas do painel administrativo
$routes->group('admin', function($routes) {
    $routes->get('login', 'Page\AdminController::login');
    $routes->get('dashboard', 'Page\AdminController::dashboard');
    $routes->get('vendas', 'Page\AdminController::vendas');
    $routes->get('consumo', 'Page\AdminController::consumo');
    $routes->get('usuarios', 'Page\AdminController::usuarios');
});
