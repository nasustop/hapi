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

Router::addGroup('/third-party', function () {
    Router::get('/request-log/enum/method', 'ThirdPartyBundle\Controller\Backend\ThirdPartyRequestLogController@actionEnumMethod', [
        'alias' => 'app.third-party.request-log.enum.method',
        'name' => '请求方式列表',
    ]);
    Router::get('/request-log/enum/status', 'ThirdPartyBundle\Controller\Backend\ThirdPartyRequestLogController@actionEnumStatus', [
        'alias' => 'app.third-party.request-log.enum.status',
        'name' => '请求状态列表',
    ]);
    Router::get('/request-log/enum/status_code', 'ThirdPartyBundle\Controller\Backend\ThirdPartyRequestLogController@actionEnumStatusCode', [
        'alias' => 'app.third-party.request-log.enum.status_code',
        'name' => '请求http status列表',
    ]);
    Router::get('/request-log/list', 'ThirdPartyBundle\Controller\Backend\ThirdPartyRequestLogController@actionList', [
        'alias' => 'app.third-party.request-log.list',
        'name' => '请求日志列表',
    ]);
}, [
    'middleware' => [
        \SystemBundle\Middleware\BackendTokenMiddleware::class,
    ],
]);
