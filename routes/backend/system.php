<?php
use Hyperf\HttpServer\Router\Router;

Router::addGroup('/system/auth', function () {
    Router::get('/menu/enum/menu_type', 'SystemBundle\Controller\Backend\SystemMenuController@actionEnumMenuType', [
        'alias' => 'app.system.menu.enum.menu_type',
        'name' => '菜单类型列表',
    ]);
    Router::get('/menu/list', 'SystemBundle\Controller\Backend\SystemMenuController@actionList', [
        'alias' => 'app.system.menu.list',
        'name' => '菜单列表',
    ]);
    Router::post('/menu/create', 'SystemBundle\Controller\Backend\SystemMenuController@actionCreate', [
        'alias' => 'app.system.menu.create',
        'name' => '添加菜单',
    ]);
    Router::post('/menu/update', 'SystemBundle\Controller\Backend\SystemMenuController@actionUpdate', [
        'alias' => 'app.system.menu.update',
        'name' => '修改菜单',
    ]);
    Router::post('/menu/delete', 'SystemBundle\Controller\Backend\SystemMenuController@actionDelete', [
        'alias' => 'app.system.menu.delete',
        'name' => '删除菜单',
    ]);

    Router::get('/role/list', 'SystemBundle\Controller\Backend\SystemRoleController@actionList', [
        'alias' => 'app.system.role.list',
        'name' => '角色列表',
    ]);
    Router::post('/role/create', 'SystemBundle\Controller\Backend\SystemRoleController@actionCreate', [
        'alias' => 'app.system.role.create',
        'name' => '添加角色',
    ]);
    Router::post('/role/update', 'SystemBundle\Controller\Backend\SystemRoleController@actionUpdate', [
        'alias' => 'app.system.role.update',
        'name' => '修改角色',
    ]);
    Router::post('/role/delete', 'SystemBundle\Controller\Backend\SystemRoleController@actionDelete', [
        'alias' => 'app.system.role.delete',
        'name' => '删除角色',
    ]);

    Router::get('/user/list', 'SystemBundle\Controller\Backend\SystemUserController@actionList', [
        'alias' => 'app.system.user.list',
        'name' => '用户列表',
    ]);
    Router::post('/user/create', 'SystemBundle\Controller\Backend\SystemUserController@actionCreate', [
        'alias' => 'app.system.user.create',
        'name' => '添加用户',
    ]);
    Router::post('/user/update', 'SystemBundle\Controller\Backend\SystemUserController@actionUpdate', [
        'alias' => 'app.system.user.update',
        'name' => '修改用户',
    ]);
    Router::post('/user/delete', 'SystemBundle\Controller\Backend\SystemUserController@actionDelete', [
        'alias' => 'app.system.user.delete',
        'name' => '删除用户',
    ]);
});
