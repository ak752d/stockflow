<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->post('api/login', 'AuthController::login');

$routes->group('api', ['filter' => 'jwt'], static function ($routes) {
    $routes->get('categories', 'CategoryController::index');
    $routes->get('categories/(:num)', 'CategoryController::show/$1');
    $routes->get('products', 'ProductController::index');
    $routes->get('products/(:num)', 'ProductController::show/$1');
    $routes->get('orders', 'OrderController::index');
    $routes->get('orders/(:num)', 'OrderController::show/$1');
    $routes->post('orders', 'OrderController::create');
    $routes->put('orders/(:num)', 'OrderController::update/$1');
});

$routes->group('api', ['filter' => 'jwt:admin'], static function ($routes) {
    $routes->post('categories', 'CategoryController::create');
    $routes->put('categories/(:num)', 'CategoryController::update/$1');
    $routes->delete('categories/(:num)', 'CategoryController::delete/$1');
    $routes->post('products', 'ProductController::create');
    $routes->put('products/(:num)', 'ProductController::update/$1');
    $routes->delete('products/(:num)', 'ProductController::delete/$1');
});
