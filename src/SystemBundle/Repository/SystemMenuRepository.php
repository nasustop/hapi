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
    public const ENUM_MENU_TYPE_MENU = 'menu';

    public const ENUM_MENU_TYPE_PAGE = 'page';

    public const ENUM_MENU_TYPE_APIS = 'apis';

    public const ENUM_MENU_TYPE = [
        self::ENUM_MENU_TYPE_MENU => '菜单',
        self::ENUM_MENU_TYPE_PAGE => '页面',
        self::ENUM_MENU_TYPE_APIS => '接口',
    ];

    public const ENUM_MENU_TYPE_DEFAULT = self::ENUM_MENU_TYPE_MENU;

    protected SystemMenuModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    public function enumMenuType(): array
    {
        return self::ENUM_MENU_TYPE;
    }

    public function enumMenuTypeDefault(): string
    {
        return self::ENUM_MENU_TYPE_DEFAULT;
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

    public function setColumnData(array $data): array
    {
        if (empty($this->getCols())) {
            return $data;
        }

        $result = [];
        foreach ($data as $key => $value) {
            if (! in_array($key, $this->getCols())) {
                continue;
            }
            if ($key === 'apis') {
                if (empty($value)) {
                    $result[$key] = null;
                } else {
                    $result[$key] = json_encode(explode("\n", $value));
                }
                continue;
            }
            $result[$key] = $value;
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
            $value['apis'] = $value['apis'] ? @json_decode($value['apis'], true) : [];
            if (! $value['apis']) {
                $value['apis'] = [];
            }
            $value['apis'] = implode("\n", $value['apis']);
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
        $listItem['apis'] = explode("\n", $listItem['apis']);
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
