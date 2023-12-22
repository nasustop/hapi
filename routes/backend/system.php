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
    Router::get('/auth/api/info', 'SystemBundle\Controller\Backend\SystemApiController@actionInfo', [
        'alias' => 'app.system.api.info',
        'name' => 'API详情',
    ]);
    Router::get('/auth/api/list', 'SystemBundle\Controller\Backend\SystemApiController@actionList', [
        'alias' => 'app.system.api.list',
        'name' => 'API列表',
    ]);
    Router::post('/auth/api/create', 'SystemBundle\Controller\Backend\SystemApiController@actionCreate', [
        'alias' => 'app.system.api.create',
        'name' => '添加API',
    ]);
    Router::post('/auth/api/update', 'SystemBundle\Controller\Backend\SystemApiController@actionUpdate', [
        'alias' => 'app.system.api.update',
        'name' => '修改API',
    ]);
    Router::post('/auth/api/delete', 'SystemBundle\Controller\Backend\SystemApiController@actionDelete', [
        'alias' => 'app.system.api.delete',
        'name' => '删除API',
    ]);
    Router::get('/auth/api/template/list', 'SystemBundle\Controller\Backend\SystemApiController@actionTemplateList', [
        'alias' => 'app.system.api.template.list',
        'name' => '菜单API模板',
    ]);
    Router::get('/auth/api/template/create', 'SystemBundle\Controller\Backend\SystemApiController@actionTemplateCreate', [
        'alias' => 'app.system.api.template.create',
        'name' => '添加API模板',
    ]);
    Router::get('/auth/api/template/update', 'SystemBundle\Controller\Backend\SystemApiController@actionTemplateUpdate', [
        'alias' => 'app.system.api.template.update',
        'name' => '修改API模板',
    ]);

    Router::get('/auth/menu/info', 'SystemBundle\Controller\Backend\SystemMenuController@actionInfo', [
        'alias' => 'app.system.menu.info',
        'name' => '菜单详情',
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
    Router::get('/auth/menu/template/list', 'SystemBundle\Controller\Backend\SystemMenuController@actionTemplateList', [
        'alias' => 'app.system.menu.template.list',
        'name' => '菜单列表模板',
    ]);
    Router::get('/auth/menu/template/create', 'SystemBundle\Controller\Backend\SystemMenuController@actionTemplateCreate', [
        'alias' => 'app.system.menu.template.create',
        'name' => '添加菜单模板',
    ]);
    Router::get('/auth/menu/template/update', 'SystemBundle\Controller\Backend\SystemMenuController@actionTemplateUpdate', [
        'alias' => 'app.system.menu.template.update',
        'name' => '修改菜单模板',
    ]);

    Router::get('/auth/role/template/list', 'SystemBundle\Controller\Backend\SystemRoleController@actionTableTemplate', [
        'alias' => 'app.system.role.template.list',
        'name' => '角色列表模板',
    ]);
    Router::get('/auth/role/template/create', 'SystemBundle\Controller\Backend\SystemRoleController@actionCreateFormTemplate', [
        'alias' => 'app.system.role.template.create',
        'name' => '添加角色模板',
    ]);
    Router::get('/auth/role/template/update', 'SystemBundle\Controller\Backend\SystemRoleController@actionUpdateFormTemplate', [
        'alias' => 'app.system.role.template.update',
        'name' => '修改角色模板',
    ]);
    Router::get('/auth/role/info', 'SystemBundle\Controller\Backend\SystemRoleController@actionInfo', [
        'alias' => 'app.system.role.info',
        'name' => '角色详情',
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

    Router::get('/auth/user/template/list', 'SystemBundle\Controller\Backend\SystemUserController@actionTemplateList', [
        'alias' => 'app.system.user.template.list',
        'name' => '用户列表模板',
    ]);

    Router::get('/auth/user/template/create', 'SystemBundle\Controller\Backend\SystemUserController@actionTemplateCreate', [
        'alias' => 'app.system.user.template.create',
        'name' => '添加用户模板',
    ]);

    Router::get('/auth/user/template/update', 'SystemBundle\Controller\Backend\SystemUserController@actionTemplateUpdate', [
        'alias' => 'app.system.user.template.update',
        'name' => '修改用户模板',
    ]);
    Router::get('/auth/user/list', 'SystemBundle\Controller\Backend\SystemUserController@actionList', [
        'alias' => 'app.system.user.list',
        'name' => '用户列表',
    ]);
    Router::get('/auth/user/info', 'SystemBundle\Controller\Backend\SystemUserController@actionInfo', [
        'alias' => 'app.system.user.info',
        'name' => '用户详情',
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
