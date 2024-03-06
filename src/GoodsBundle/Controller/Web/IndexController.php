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

namespace GoodsBundle\Controller\Web;

use App\Controller\AbstractController;
use GoodsBundle\Repository\GoodsSpuRepository;
use GoodsBundle\Service\GoodsCategoryService;
use GoodsBundle\Service\GoodsSpuService;

class IndexController extends AbstractController
{
    protected GoodsCategoryService $goodsCategoryService;

    protected GoodsSpuService $goodsSpuService;

    /**
     * get GoodsCategoryService.
     */
    public function getGoodsCategoryService(): GoodsCategoryService
    {
        if (empty($this->goodsCategoryService)) {
            $this->goodsCategoryService = make(GoodsCategoryService::class);
        }
        return $this->goodsCategoryService;
    }

    /**
     * get GoodsSpuService.
     */
    public function getGoodsSpuService(): GoodsSpuService
    {
        if (empty($this->goodsSpuService)) {
            $this->goodsSpuService = make(GoodsSpuService::class);
        }
        return $this->goodsSpuService;
    }

    public function actionIndex(): \Psr\Http\Message\ResponseInterface
    {
        $spuFilter = [
            'status' => GoodsSpuRepository::ENUM_STATUS_ON_SALE,
        ];
        $spuName = $this->getRequest()->input('name');
        if (! empty($spuName)) {
            $spuFilter['spu_name|contains'] = $spuName;
        }
        $category_id = $this->getRequest()->route('category_id');
        if (! empty($category_id)) {
            $spuFilter['category_id'] = $category_id;
        }
        $page = $this->getRequest()->input('page', 1);
        $page_size = $this->getRequest()->input('page_size', 20);

        $orderBy = [
            'sort' => 'desc',
            'spu_id' => 'desc',
        ];
        // 获取分类，列表
        $spuList = $this->getGoodsSpuService()->getGoodsSpuList($spuFilter, '*', $page, $page_size, $orderBy);
        $categoryList = $this->getGoodsCategoryService()->getRepository()->getCategoryCascadeData();

        $url = '/index.html';
        if (! empty($category_id)) {
            $url = '/category/' . $category_id . '.html';
        }
        return $this->getRender()->render('index', [
            'categoryList' => $categoryList,
            'spuList' => $spuList['list'],
            'spuTotal' => $spuList['total'],
            'page' => $page,
            'allPage' => ceil($spuList['total'] / $page_size),
            'url' => $url,
        ]);
    }

    public function actionDetail(): \Psr\Http\Message\ResponseInterface
    {
        $spu_id = $this->getRequest()->route('spu_id');
        $spu_info = $this->getGoodsSpuService()->getGoodsSpuInfo(['spu_id' => $spu_id]);
        return $this->getRender()->render('detail', ['spu_info' => $spu_info]);
    }
}
