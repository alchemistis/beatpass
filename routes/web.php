<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/user/{id}', ['middleware' => 'basic.auth', 'uses' => 'UserController@show']);
$router->delete('/user/{id}', ['middleware' => 'basic.auth', 'uses' => 'UserController@delete']);
$router->post('/user', 'UserController@create');
$router->post('/user/auth', 'UserController@authenticate');
$router->post('/user/update/{id}', 'UserController@update');

$router->get('/compra/{token}', 'CompraController@show');
$router->get('/compra/all/{id}', 'CompraController@all');

$router->get('/evento', 'EventoController@all');
$router->get('/evento/{slug}', 'EventoController@show');

$router->get('/ingresso/{idEvento}', 'IngressoController@show');

//$router->post('/carrinho/{userId}', 'CarrinhoController@create');
$router->get('/carrinho/{id}', 'CarrinhoController@show');
$router->post('/carrinho/{id}', 'CarrinhoController@addProduct');
$router->delete('/carrinho/{id}', 'CarrinhoController@removeProduct');
$router->post('/carrinho/{id}/checkout', 'CarrinhoController@checkout');
