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

Router::addGroup('/goods', function () {
    Router::get('/category/info', 'GoodsBundle\Controller\Backend\GoodsCategoryController@actionInfo', [
        'alias' => 'app.goods.category.info',
        'name' => '商品分类详情',
    ]);
    Router::get('/category/list', 'GoodsBundle\Controller\Backend\GoodsCategoryController@actionList', [
        'alias' => 'app.goods.category.list',
        'name' => '商品分类列表',
    ]);
    Router::get('/category/cascade', 'GoodsBundle\Controller\Backend\GoodsCategoryController@actionCascadeList', [
        'alias' => 'app.goods.category.cascade',
        'name' => '商品分类级联选择器列表',
    ]);
    Router::post('/category/create', 'GoodsBundle\Controller\Backend\GoodsCategoryController@actionCreate', [
        'alias' => 'app.goods.category.create',
        'name' => '添加商品分类',
    ]);
    Router::post('/category/update', 'GoodsBundle\Controller\Backend\GoodsCategoryController@actionUpdate', [
        'alias' => 'app.goods.category.update',
        'name' => '修改商品分类',
    ]);
    Router::post('/category/delete', 'GoodsBundle\Controller\Backend\GoodsCategoryController@actionDelete', [
        'alias' => 'app.goods.category.delete',
        'name' => '删除商品分类',
    ]);
    Router::get('/category/template/list', 'GoodsBundle\Controller\Backend\GoodsCategoryController@actionTemplateList', [
        'alias' => 'app.goods.category.template.list',
        'name' => '商品分类模板',
    ]);
    Router::get('/category/template/create', 'GoodsBundle\Controller\Backend\GoodsCategoryController@actionTemplateCreate', [
        'alias' => 'app.goods.category.template.create',
        'name' => '添加商品分类模板',
    ]);
    Router::get('/category/template/update', 'GoodsBundle\Controller\Backend\GoodsCategoryController@actionTemplateUpdate', [
        'alias' => 'app.goods.category.template.update',
        'name' => '修改商品分类模板',
    ]);

    Router::get('/brand/info', 'GoodsBundle\Controller\Backend\GoodsBrandController@actionInfo', [
        'alias' => 'app.goods.brand.info',
        'name' => '商品品牌详情',
    ]);
    Router::get('/brand/list', 'GoodsBundle\Controller\Backend\GoodsBrandController@actionList', [
        'alias' => 'app.goods.brand.list',
        'name' => '商品品牌列表',
    ]);
    Router::post('/brand/create', 'GoodsBundle\Controller\Backend\GoodsBrandController@actionCreate', [
        'alias' => 'app.goods.brand.create',
        'name' => '添加商品品牌',
    ]);
    Router::post('/brand/update', 'GoodsBundle\Controller\Backend\GoodsBrandController@actionUpdate', [
        'alias' => 'app.goods.brand.update',
        'name' => '修改商品品牌',
    ]);
    Router::post('/brand/delete', 'GoodsBundle\Controller\Backend\GoodsBrandController@actionDelete', [
        'alias' => 'app.goods.brand.delete',
        'name' => '删除商品品牌',
    ]);
    Router::get('/brand/template/list', 'GoodsBundle\Controller\Backend\GoodsBrandController@actionTemplateList', [
        'alias' => 'app.goods.brand.template.list',
        'name' => '商品品牌模板',
    ]);
    Router::get('/brand/template/create', 'GoodsBundle\Controller\Backend\GoodsBrandController@actionTemplateCreate', [
        'alias' => 'app.goods.brand.template.create',
        'name' => '添加商品品牌模板',
    ]);
    Router::get('/brand/template/update', 'GoodsBundle\Controller\Backend\GoodsBrandController@actionTemplateUpdate', [
        'alias' => 'app.goods.brand.template.update',
        'name' => '修改商品品牌模板',
    ]);

    Router::get('/spec/info', 'GoodsBundle\Controller\Backend\GoodsSpecController@actionInfo', [
        'alias' => 'app.goods.spec.info',
        'name' => '商品品牌详情',
    ]);
    Router::get('/spec/list', 'GoodsBundle\Controller\Backend\GoodsSpecController@actionList', [
        'alias' => 'app.goods.spec.list',
        'name' => '商品品牌列表',
    ]);
    Router::post('/spec/create', 'GoodsBundle\Controller\Backend\GoodsSpecController@actionCreate', [
        'alias' => 'app.goods.spec.create',
        'name' => '添加商品品牌',
    ]);
    Router::post('/spec/update', 'GoodsBundle\Controller\Backend\GoodsSpecController@actionUpdate', [
        'alias' => 'app.goods.spec.update',
        'name' => '修改商品品牌',
    ]);
    Router::post('/spec/delete', 'GoodsBundle\Controller\Backend\GoodsSpecController@actionDelete', [
        'alias' => 'app.goods.spec.delete',
        'name' => '删除商品品牌',
    ]);
    Router::get('/spec/template/list', 'GoodsBundle\Controller\Backend\GoodsSpecController@actionTemplateList', [
        'alias' => 'app.goods.spec.template.list',
        'name' => '商品品牌模板',
    ]);
    Router::get('/spec/template/create', 'GoodsBundle\Controller\Backend\GoodsSpecController@actionTemplateCreate', [
        'alias' => 'app.goods.spec.template.create',
        'name' => '添加商品品牌模板',
    ]);
    Router::get('/spec/template/update', 'GoodsBundle\Controller\Backend\GoodsSpecController@actionTemplateUpdate', [
        'alias' => 'app.goods.spec.template.update',
        'name' => '修改商品品牌模板',
    ]);

    Router::get('/params/info', 'GoodsBundle\Controller\Backend\GoodsParamsController@actionInfo', [
        'alias' => 'app.goods.params.info',
        'name' => '商品参数详情',
    ]);
    Router::get('/params/list', 'GoodsBundle\Controller\Backend\GoodsParamsController@actionList', [
        'alias' => 'app.goods.params.list',
        'name' => '商品参数列表',
    ]);
    Router::post('/params/create', 'GoodsBundle\Controller\Backend\GoodsParamsController@actionCreate', [
        'alias' => 'app.goods.params.create',
        'name' => '添加商品参数',
    ]);
    Router::post('/params/update', 'GoodsBundle\Controller\Backend\GoodsParamsController@actionUpdate', [
        'alias' => 'app.goods.params.update',
        'name' => '修改商品参数',
    ]);
    Router::post('/params/delete', 'GoodsBundle\Controller\Backend\GoodsParamsController@actionDelete', [
        'alias' => 'app.goods.params.delete',
        'name' => '删除商品参数',
    ]);
    Router::get('/params/template/list', 'GoodsBundle\Controller\Backend\GoodsParamsController@actionTemplateList', [
        'alias' => 'app.goods.params.template.list',
        'name' => '商品参数模板',
    ]);
    Router::get('/params/template/create', 'GoodsBundle\Controller\Backend\GoodsParamsController@actionTemplateCreate', [
        'alias' => 'app.goods.params.template.create',
        'name' => '添加商品参数模板',
    ]);
    Router::get('/params/template/update', 'GoodsBundle\Controller\Backend\GoodsParamsController@actionTemplateUpdate', [
        'alias' => 'app.goods.params.template.update',
        'name' => '修改商品参数模板',
    ]);

    Router::get('/spu/info', 'GoodsBundle\Controller\Backend\GoodsSpuController@actionInfo', [
        'alias' => 'app.goods.spu.info',
        'name' => '商品spu详情',
    ]);
    Router::get('/spu/list', 'GoodsBundle\Controller\Backend\GoodsSpuController@actionList', [
        'alias' => 'app.goods.spu.list',
        'name' => '商品spu列表',
    ]);
    Router::post('/spu/create', 'GoodsBundle\Controller\Backend\GoodsSpuController@actionCreate', [
        'alias' => 'app.goods.spu.create',
        'name' => '添加商品spu',
    ]);
    Router::post('/spu/update', 'GoodsBundle\Controller\Backend\GoodsSpuController@actionUpdate', [
        'alias' => 'app.goods.spu.update',
        'name' => '修改商品spu',
    ]);
    Router::post('/spu/update_some', 'GoodsBundle\Controller\Backend\GoodsSpuController@actionUpdateSome', [
        'alias' => 'app.goods.spu.update_some',
        'name' => '修改商品spu状态',
    ]);
    Router::post('/spu/delete', 'GoodsBundle\Controller\Backend\GoodsSpuController@actionDelete', [
        'alias' => 'app.goods.spu.delete',
        'name' => '删除商品spu',
    ]);
}, [
    'middleware' => [
        \SystemBundle\Middleware\BackendTokenMiddleware::class,
    ],
]);
