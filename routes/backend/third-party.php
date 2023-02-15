<?php

use Hyperf\HttpServer\Router\Router;

Router::addGroup('/mini-app', function () {
    Router::get('/test', 'WechatBundle\Controller\TestController@actionTest');
});



Router::addGroup('/third-party', function () {
    Router::get('/request-log/enum/method', 'ThirdPartyBundle\Controller\Backend\ThirdPartyRequestLogController@actionEnumRequestMethod', [
        'alias' => 'app.third-party.request-log.enum.method',
        'name' => '请求日志列表',
    ]);
    Router::get('/request-log/enum/status', 'ThirdPartyBundle\Controller\Backend\ThirdPartyRequestLogController@actionEnumRequestStatus', [
        'alias' => 'app.third-party.request-log.enum.status',
        'name' => '请求日志列表',
    ]);
    Router::get('/request-log/enum/status_code', 'ThirdPartyBundle\Controller\Backend\ThirdPartyRequestLogController@actionEnumRequestStatusCode', [
        'alias' => 'app.third-party.request-log.enum.status_code',
        'name' => '请求日志列表',
    ]);
});

Router::addGroup('/third-party', function () {
    Router::get('/request-log/list', 'ThirdPartyBundle\Controller\Backend\ThirdPartyRequestLogController@actionList', [
        'alias' => 'app.third-party.request-log.list',
        'name' => '请求日志列表',
    ]);
});
