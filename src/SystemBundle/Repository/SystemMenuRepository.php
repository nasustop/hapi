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
namespace SystemBundle\Repository;

use App\Repository\Repository;
use SystemBundle\Model\SystemMenuModel;

class SystemMenuRepository extends Repository
{
    protected SystemMenuModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): SystemMenuModel
    {
        if (empty($this->model)) {
            $this->model = make(SystemMenuModel::class);
        }
        return $this->model;
    }

    public function formatColumnData(array $data): array
    {
        $result = parent::formatColumnData($data);
        if (! empty($result)) {
            $result['is_show'] = $result['is_show'] === 1;
        }
        return $result;
    }

    /**
     * @return array ['tree' => "array", 'list' => "array", 'total' => "int"]
     */
    public function findTreeByMenuIds(array $menu_ids = []): array
    {
        $filter = [];
        $all_data = $this->getLists(filter: $filter, orderBy: ['parent_id' => 'asc', 'sort' => 'asc']);
        $tree = []; // 顶级菜单
        $list = []; // 所有菜单列表
        foreach ($all_data as $key => $value) {
            if (! $value['parent_id']) {
                $value['level'] = 0;
                $tree[] = $value;
            }
            $all_data[$key] = $value;
        }
        if (empty($menu_ids)) {
            // 没有指定范围的menu_id，则默认查询所有的菜单
            $menu_ids = array_column($all_data, 'menu_id');
        }
        foreach ($tree as $key => $value) {
            $value['parent_name'] = '顶级菜单';
            // 查询顶级菜单的子节点
            $tree[$key] = $this->findTreeByParent(parent: $value, all_data: $all_data, menu_ids: $menu_ids, list: $list);
        }
        return [
            'tree' => $tree,
            'list' => $list,
            'total' => count($list),
        ];
    }

    /**
     * 根据某个父节点获取它的所有子节点集合.
     */
    protected function findTreeByParent(array $parent, array $all_data = [], array $menu_ids = [], array &$list = []): array
    {
        if (empty($parent)) {
            return [];
        }
        if (empty($menu_ids)) {
            return [];
        }
        if (empty($all_data)) {
            $filter = [];
            $all_data = $this->getLists(filter: $filter, orderBy: ['parent_id' => 'asc', 'sort' => 'asc']);
        }
        foreach ($all_data as $value) {
            if ($value['parent_id'] !== $parent['menu_id']) {
                continue;
            }
            $value['level'] = $parent['level'] + 1;
            $value['parent_name'] = $parent['menu_name'];
            $children = $this->findTreeByParent(parent: $value, all_data: $all_data, menu_ids: $menu_ids, list: $list);
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
        if (! in_array($parent['menu_id'], $menu_ids)) {
            return [];
        }
        $list[] = $listItem;
        return $parent;
    }
}
