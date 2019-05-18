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

$router->group(['prefix' => 'api'], function () use ($router) {
	$router->group(['namespace'=>'Guest'], function () use ($router) {
		$router->post('/register', 'AuthController@register');
		$router->post('/login', 'AuthController@login');
	});
	$router->group(['namespace'=>'User', 'middleware'=>'auth'], function () use ($router) {
		$router->get('/me', 'AuthController@me');
		$router->get('/logout', 'AuthController@loutout');
		$router->post('/users/search', 'UserController@search');
		$router->get('/users/{user_id}', 'UserController@show');
	});
});