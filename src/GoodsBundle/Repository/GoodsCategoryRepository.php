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

namespace GoodsBundle\Repository;

use App\Repository\Repository;
use GoodsBundle\Model\GoodsCategoryModel;

class GoodsCategoryRepository extends Repository
{
    protected GoodsCategoryModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): GoodsCategoryModel
    {
        if (empty($this->model)) {
            $this->model = make(GoodsCategoryModel::class);
        }
        return $this->model;
    }

    public function formatColumnData(array $data): array
    {
        $data = parent::setColumnData($data);
        foreach ($data as $key => $value) {
            if ($key === 'is_show') {
                $data[$key] = $value === 1;
            }
        }
        return $data;
    }

    /**
     * @return array ['tree' => "array", 'list' => "array"]
     */
    public function findTreeByCategoryIds(array $category_ids = []): array
    {
        $filter = [];
        $all_data = $this->getLists(filter: $filter, orderBy: ['parent_id' => 'asc', 'sort' => 'asc']);
        $tree = []; // 顶级分类
        $list = []; // 所有分类列表
        foreach ($all_data as $key => $value) {
            if (! $value['parent_id']) {
                $value['level'] = 0;
                $tree[] = $value;
            }
            $all_data[$key] = $value;
        }
        if (empty($category_ids)) {
            // 没有指定范围的category_id，则默认查询所有的分类
            $category_ids = array_column($all_data, 'category_id');
        }
        foreach ($tree as $key => $value) {
            $value['parent_name'] = '顶级分类';
            // 查询顶级菜单的子节点
            $tree[$key] = $this->findTreeByParent($value, $all_data, $category_ids, $list);
        }
        return [
            'tree' => $tree,
            'list' => $list,
        ];
    }

    /**
     * 根据某个父节点获取它的所有子节点集合.
     */
    protected function findTreeByParent(array $parent, array $all_data = [], array $category_ids = [], array &$list = []): array
    {
        if (empty($parent)) {
            return [];
        }
        if (empty($category_ids)) {
            return [];
        }
        if (empty($all_data)) {
            $filter = [];
            $all_data = $this->getLists(filter: $filter, orderBy: ['parent_id' => 'asc', 'sort' => 'asc']);
        }
        foreach ($all_data as $value) {
            if ($value['parent_id'] !== $parent['category_id']) {
                continue;
            }
            $value['level'] = $parent['level'] + 1;
            $value['parent_name'] = $parent['category_name'];
            $children = $this->findTreeByParent($value, $all_data, $category_ids, $list);
            if (empty($children)) {
                continue;
            }
            $parent['children'][] = $children;
        }
        $parent['has_children'] = isset($parent['children']);
        $listItem = $parent;
        if ($parent['has_children']) {
            unset($listItem['children']);
            $list[] = $listItem;
            return $parent;
        }
        if (! in_array($parent['category_id'], $category_ids)) {
            return [];
        }
        $list[] = $listItem;
        return $parent;
    }
}
