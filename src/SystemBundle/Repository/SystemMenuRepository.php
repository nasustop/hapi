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
use Hyperf\Di\Annotation\Inject;
use SystemBundle\Model\SystemMenuModel;

class SystemMenuRepository extends Repository
{
    public const MENU_TYPE_MENU = 'menu';

    public const MENU_TYPE_APIS = 'apis';

    public const ENUM_MENU_TYPE = [
        self::MENU_TYPE_MENU => '菜单',
        self::MENU_TYPE_APIS => '权限集',
    ];

    public const ENUM_MENU_TYPE_DEFAULT = self::MENU_TYPE_MENU;

    #[Inject]
    protected SystemMenuModel $model;

    /**
     * The table all columns.
     */
    protected array $cols = ['menu_id', 'parent_id', 'menu_name', 'menu_alias', 'sort', 'is_show', 'menu_type', 'apis', 'created_at', 'updated_at'];

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): SystemMenuModel
    {
        return $this->model;
    }

    public function enumMenuType(): array
    {
        return self::ENUM_MENU_TYPE;
    }

    public function enumMenuTypeDefault(): string
    {
        return self::ENUM_MENU_TYPE_DEFAULT;
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
        $filter = [
            'is_show' => 1,
        ];
        $all_data = $this->getLists($filter, '*', 0, 0, ['parent_id' => 'asc', 'sort' => 'asc']);
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
            $tree[$key] = $this->findTreeByParent($value, $all_data, $menu_ids, $list);
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
            $filter = [
                'is_show' => 1,
            ];
            $all_data = $this->getLists($filter, '*', 0, 0, ['parent_id' => 'asc', 'sort' => 'asc']);
        }
        foreach ($all_data as $value) {
            if ($value['parent_id'] !== $parent['menu_id']) {
                continue;
            }
            $value['level'] = $parent['level'] + 1;
            $value['parent_name'] = $parent['menu_name'];
            $children = $this->findTreeByParent($value, $all_data, $menu_ids, $list);
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
