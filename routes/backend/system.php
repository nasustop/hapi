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
}, [
    'middleware' => [
        \SystemBundle\Middleware\BackendTokenMiddleware::class,
    ],
]);
