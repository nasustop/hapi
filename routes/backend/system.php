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

Router::post('/login', 'SystemBundle\Controller\Backend\LoginController@actionLogin', [
    'alias' => 'app.system.login',
    'name' => '登录',
]);

Router::post('/refresh', 'SystemBundle\Controller\Backend\LoginController@actionRefresh', [
    'alias' => 'app.system.refresh',
    'name' => '刷新登录凭据',
]);

Router::get('/info', 'SystemBundle\Controller\Backend\LoginController@actionInfo', [
    'alias' => 'app.system.info',
    'name' => '详情',
    'middleware' => [
        \SystemBundle\Middleware\BackendTokenMiddleware::class,
    ],
]);

Router::post('/logout', 'SystemBundle\Controller\Backend\LoginController@actionLogout', [
    'alias' => 'app.system.logout',
    'name' => '退出',
    'middleware' => [
        \SystemBundle\Middleware\BackendTokenMiddleware::class,
    ],
]);

Router::addGroup('/system', function () {
    Router::get('/auth/menu/enum/menu_type', 'SystemBundle\Controller\Backend\SystemMenuController@actionEnumMenuType', [
        'alias' => 'app.system.menu.enum.menu_type',
        'name' => '菜单类型列表',
    ]);
    Router::get('/auth/menu/list', 'SystemBundle\Controller\Backend\SystemMenuController@actionList', [
        'alias' => 'app.system.menu.list',
        'name' => '菜单列表',
    ]);
    Router::post('/auth/menu/create', 'SystemBundle\Controller\Backend\SystemMenuController@actionCreate', [
        'alias' => 'app.system.menu.create',
        'name' => '添加菜单',
    ]);
    Router::post('/auth/menu/update', 'SystemBundle\Controller\Backend\SystemMenuController@actionUpdate', [
        'alias' => 'app.system.menu.update',
        'name' => '修改菜单',
    ]);
    Router::post('/auth/menu/delete', 'SystemBundle\Controller\Backend\SystemMenuController@actionDelete', [
        'alias' => 'app.system.menu.delete',
        'name' => '删除菜单',
    ]);

    Router::get('/auth/role/list', 'SystemBundle\Controller\Backend\SystemRoleController@actionList', [
        'alias' => 'app.system.role.list',
        'name' => '角色列表',
    ]);
    Router::post('/auth/role/create', 'SystemBundle\Controller\Backend\SystemRoleController@actionCreate', [
        'alias' => 'app.system.role.create',
        'name' => '添加角色',
    ]);
    Router::post('/auth/role/update', 'SystemBundle\Controller\Backend\SystemRoleController@actionUpdate', [
        'alias' => 'app.system.role.update',
        'name' => '修改角色',
    ]);
    Router::post('/auth/role/delete', 'SystemBundle\Controller\Backend\SystemRoleController@actionDelete', [
        'alias' => 'app.system.role.delete',
        'name' => '删除角色',
    ]);

    Router::get('/auth/user/list', 'SystemBundle\Controller\Backend\SystemUserController@actionList', [
        'alias' => 'app.system.user.list',
        'name' => '用户列表',
    ]);
    Router::post('/auth/user/create', 'SystemBundle\Controller\Backend\SystemUserController@actionCreate', [
        'alias' => 'app.system.user.create',
        'name' => '添加用户',
    ]);
    Router::post('/auth/user/update', 'SystemBundle\Controller\Backend\SystemUserController@actionUpdate', [
        'alias' => 'app.system.user.update',
        'name' => '修改用户',
    ]);
    Router::post('/auth/user/delete', 'SystemBundle\Controller\Backend\SystemUserController@actionDelete', [
        'alias' => 'app.system.user.delete',
        'name' => '删除用户',
    ]);

    Router::post('/uploads/image/upload', 'SystemBundle\Controller\Backend\SystemUploadImageController@actionUpload', [
        'alias' => 'app.system.image.upload',
        'name' => '上传图片',
    ]);
    Router::get('/uploads/image/list', 'SystemBundle\Controller\Backend\SystemUploadImageController@actionList', [
        'alias' => 'app.system.image.list',
        'name' => '图片列表',
    ]);
    Router::post('/uploads/image/delete', 'SystemBundle\Controller\Backend\SystemUploadImageController@actionDelete', [
        'alias' => 'app.system.image.delete',
        'name' => '删除图片',
    ]);

    Router::get('/operation_log/list', 'SystemBundle\Controller\Backend\SystemOperationLogController@actionList', [
        'alias' => 'app.system.operation_log.list',
        'name' => '日志列表',
    ]);

    Router::get('/wechat/setting/enum/driver', 'SystemBundle\Controller\Backend\SystemWechatController@actionEnumDriver', [
        'alias' => 'app.system.wechat.enum.driver',
        'name' => '微信账号类型',
    ]);
    Router::get('/wechat/setting/list', 'SystemBundle\Controller\Backend\SystemWechatController@actionList', [
        'alias' => 'app.system.wechat.list',
        'name' => '微信配置列表',
    ]);
    Router::post('/wechat/setting/create', 'SystemBundle\Controller\Backend\SystemWechatController@actionCreate', [
        'alias' => 'app.system.wechat.create',
        'name' => '添加微信配置',
    ]);
    Router::post('/wechat/setting/update', 'SystemBundle\Controller\Backend\SystemWechatController@actionUpdate', [
        'alias' => 'app.system.wechat.update',
        'name' => '微信配置用户',
    ]);
    Router::post('/wechat/setting/delete', 'SystemBundle\Controller\Backend\SystemWechatController@actionDelete', [
        'alias' => 'app.system.wechat.delete',
        'name' => '微信配置用户',
    ]);

    Router::post('/uploads/csv/template/download', 'SystemBundle\Controller\Backend\SystemUploadFileController@actionDownloadUploadTemplate', [
        'alias' => 'app.system.uploads.csv.template.download',
        'name' => '下载上传模板',
    ]);
    Router::post('/uploads/csv/upload', 'SystemBundle\Controller\Backend\SystemUploadFileController@actionUploadFile', [
        'alias' => 'app.system.uploads.csv.upload',
        'name' => '上传csv文件',
    ]);
    Router::get('/uploads/csv/list', 'SystemBundle\Controller\Backend\SystemUploadFileController@actionList', [
        'alias' => 'app.system.uploads.csv.list',
        'name' => '获取上传记录',
    ]);
    Router::post('/uploads/csv/export/error', 'SystemBundle\Controller\Backend\SystemUploadFileController@actionExportErrorFile', [
        'alias' => 'app.system.uploads.csv.export.error',
        'name' => '下载错误详情',
    ]);

    Router::post('/export/download', 'SystemBundle\Controller\Backend\SystemExportFileController@actionExport', [
        'alias' => 'app.system.export.download',
        'name' => '文件导出',
    ]);
    Router::get('/export/type/list', 'SystemBundle\Controller\Backend\SystemExportFileController@actionExportTypeList', [
        'alias' => 'app.system.export.type.list',
        'name' => '获取导出类型',
    ]);
    Router::get('/export/log/list', 'SystemBundle\Controller\Backend\SystemExportFileController@actionList', [
        'alias' => 'app.system.export.log.list',
        'name' => '获取导出记录',
    ]);
}, [
    'middleware' => [
        \SystemBundle\Middleware\BackendTokenMiddleware::class,
    ],
]);
