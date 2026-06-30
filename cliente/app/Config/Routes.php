<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('inicio', 'Home::index');
$routes->get('produtos', 'Home::produtos');
$routes->get('carrinho', 'Home::carrinho');
$routes->get('checkout', 'Home::checkout');
$routes->get('nota', 'Home::nota');

// Rotas da API removidas, o cliente agora consome a API externa
