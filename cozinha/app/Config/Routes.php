<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('pedidos', 'Home::index');
$routes->get('pedidos/(:num)', 'Home::detalhes/$1');
