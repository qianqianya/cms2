<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->resource('/wxuser',WxuserController::class);

    $router->get('/wxsendmsg','WxmassController@sendMsgView');      //
    $router->post('/wxsendmsg','WxmassController@sendMsg');
    $router->get('/wxtags','WxmassController@tags');
});
