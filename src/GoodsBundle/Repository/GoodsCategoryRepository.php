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
use Hyperf\HttpMessage\Exception\BadRequestHttpException;

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
        $all_data = $this->getLists($filter, '*', 1, -1, ['parent_id' => 'asc', 'sort' => 'asc']);
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

    public function getCategoryCascadeData(): array
    {
        $data = $this->findTreeByCategoryIds();
        return $this->recursionCascadeData($data['tree']);
    }

    public function getCategoryCascadeParentIds($categoryIds, $num = 1): array
    {
        if ($num > 10) {
            throw new BadRequestHttpException('getCategoryParentData方法陷入死循环');
        }
        $data = $this->getLists(['category_id' => $categoryIds]);
        $data = array_column($data, null, 'category_id');
        $parentIds = array_values(array_unique(array_filter(array_column($data, 'parent_id'))));
        $parentData = [];
        if (! empty($parentIds)) {
            $parentData = $this->getCategoryCascadeParentIds($parentIds, $num + 1);
        }
        foreach ($data as $id => $value) {
            if (empty($value['parent_id'])) {
                $data[$id]['parent_data'] = [$id];
                continue;
            }
            $data[$id]['parent_data'] = array_merge($parentData[$value['parent_id']]['parent_data'] ?? [$value['parent_id']], [$value['category_id']]);
        }
        return $data;
    }

    /**
     * 获取分类的上级ID数组，请求数据为[[3],[9]]，返回数据为[[1,2,3],[8,9]].
     */
    public function getParentIdsByCategoryIds(array $categoryIdData, int $num = 1): array
    {
        if ($num > 10) {
            throw new BadRequestHttpException('getParentIdsByCategoryIds 方法陷入死循环');
        }
        $category_ids = [];
        foreach ($categoryIdData as $key => $value) {
            if (empty($value[0])) {
                $categoryIdData[$key] = null;
            }
            $category_ids[] = $value[0];
        }
        $categoryIdData = array_values(array_filter($categoryIdData));

        $categoryData = $this->getLists(['category_id' => $category_ids]);
        $categoryData = array_column($categoryData, null, 'category_id');
        $parentIds = [];
        foreach ($categoryIdData as $key => $value) {
            if (empty($categoryData[$value[0]]['parent_id'])) {
                continue;
            }
            $parentIds[] = $categoryData[$value[0]]['parent_id'];
            $categoryIdData[$key] = array_merge([$categoryData[$value[0]]['parent_id']], $value);
        }

        if (! empty($parentIds)) {
            $categoryIdData = $this->getParentIdsByCategoryIds($categoryIdData, $num + 1);
        }
        return $categoryIdData;
    }

    /**
     * 根据某个父节点获取它的所有子节点集合.
     */
    protected function findTreeByParent(array $parent, array $all_data = [], array $category_ids = [], array &$list = [], int $num = 1): array
    {
        if ($num > 10) {
            throw new BadRequestHttpException('findTreeByParent 方法陷入死循环');
        }
        if (empty($parent)) {
            return [];
        }
        if (empty($category_ids)) {
            return [];
        }
        if (empty($all_data)) {
            $filter = [];
            $all_data = $this->getLists($filter, ['parent_id' => 'asc', 'sort' => 'asc']);
        }
        foreach ($all_data as $value) {
            if ($value['parent_id'] !== $parent['category_id']) {
                continue;
            }
            $value['level'] = $parent['level'] + 1;
            $value['parent_name'] = $parent['category_name'];
            $children = $this->findTreeByParent($value, $all_data, $category_ids, $list, $num + 1);
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

    protected function recursionCascadeData(array $data, int $num = 1): array
    {
        if ($num > 10) {
            throw new BadRequestHttpException('recursionCascadeData 方法陷入死循环');
        }
        $result = [];
        foreach ($data as $value) {
            $item = [
                'value' => $value['category_id'],
                'label' => $value['category_name'],
            ];
            if ($value['has_children']) {
                $item['children'] = $this->recursionCascadeData($value['children'], $num + 1);
            }
            $result[] = $item;
        }
        return $result;
    }
}
