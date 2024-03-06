<?php

declare(strict_types=1);
/**
 * This file is part of Hapi.
 *
 * @link     https://www.nasus.top
 * @document https://wiki.nasus.top
 * @contact  xupengfei@xupengfei.net
 * @license  https://github.com/nasustop/hapi/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;
use SystemBundle\Middleware\SystemOperationLogMiddleware;

// Install
Router::addRoute('GET', '/install', 'App\Controller\IndexController@actionInstall', ['alias' => 'install']);
Router::addRoute('GET', '/uninstall', 'App\Controller\IndexController@actionUninstall', ['alias' => 'uninstall']);
// Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index', ['alias' => 'index']);
// Web
Router::addRoute('GET', '/', 'GoodsBundle\Controller\Web\IndexController@actionIndex', ['alias' => 'web.index.default']);
Router::addRoute('GET', '/index.html', 'GoodsBundle\Controller\Web\IndexController@actionIndex', ['alias' => 'web.index.html']);
Router::addRoute('GET', '/category/{category_id}.html', 'GoodsBundle\Controller\Web\IndexController@actionIndex', ['alias' => 'web.index.category']);
Router::addRoute('GET', '/detail/{spu_id}.html', 'GoodsBundle\Controller\Web\IndexController@actionDetail', ['alias' => 'web.detail']);

Router::get('/favicon.ico', function () {
    return '';
}, ['alias' => 'favicon']);

Router::addGroup('/api/backend', function () {
    loadDirFiles(BASE_PATH . '/routes/backend');
}, [
    'middleware' => [
        SystemOperationLogMiddleware::class,
    ],
]);
