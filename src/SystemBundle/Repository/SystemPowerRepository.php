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

use App\Exception\RuntimeErrorException;
use App\Repository\Repository;
use SystemBundle\Model\SystemPowerModel;

class SystemPowerRepository extends Repository
{
    public const ENUM_PARENT_TYPE_USER = 'user';

    public const ENUM_PARENT_TYPE_ROLE = 'role';

    public const ENUM_PARENT_TYPE_MENU = 'menu';

    public const ENUM_PARENT_TYPE = [
        self::ENUM_PARENT_TYPE_USER => 'user',
        self::ENUM_PARENT_TYPE_ROLE => 'role',
        self::ENUM_PARENT_TYPE_MENU => 'menu',
    ];

    public const ENUM_PARENT_TYPE_DEFAULT = self::ENUM_PARENT_TYPE_USER;

    public const ENUM_CHILDREN_TYPE_ROLE = 'role';

    public const ENUM_CHILDREN_TYPE_MENU = 'menu';

    public const ENUM_CHILDREN_TYPE_API = 'api';

    public const ENUM_CHILDREN_TYPE = [
        self::ENUM_CHILDREN_TYPE_ROLE => 'role',
        self::ENUM_CHILDREN_TYPE_MENU => 'menu',
        self::ENUM_CHILDREN_TYPE_API => 'api',
    ];

    public const ENUM_CHILDREN_TYPE_DEFAULT = self::ENUM_CHILDREN_TYPE_ROLE;

    protected SystemPowerModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    public function enumParentType(): array
    {
        return self::ENUM_PARENT_TYPE;
    }

    public function enumParentTypeDefault(): string
    {
        return self::ENUM_PARENT_TYPE_DEFAULT;
    }

    public function enumChildrenType(): array
    {
        return self::ENUM_CHILDREN_TYPE;
    }

    public function enumChildrenTypeDefault(): string
    {
        return self::ENUM_CHILDREN_TYPE_DEFAULT;
    }

    /**
     * get Model.
     */
    public function getModel(): SystemPowerModel
    {
        if (empty($this->model)) {
            $this->model = make(SystemPowerModel::class);
        }
        return $this->model;
    }

    public function batchInsertByAuth(int $parent_id, string $parent_type, array $children_ids, string $children_type): bool
    {
        if (! in_array($parent_type, self::ENUM_PARENT_TYPE)) {
            throw new RuntimeErrorException('parent_type must in ' . implode(',', self::ENUM_PARENT_TYPE));
        }
        if (! in_array($children_type, self::ENUM_CHILDREN_TYPE)) {
            throw new RuntimeErrorException('children_type must in ' . implode(',', self::ENUM_CHILDREN_TYPE));
        }
        $this->deleteBy([
            'parent_id' => $parent_id,
            'children_type' => $children_type,
        ]);
        $insert_list = [];
        foreach ($children_ids as $children_id) {
            if (empty($children_id)) {
                continue;
            }
            $insert_list[] = [
                'parent_id' => $parent_id,
                'parent_type' => $parent_type,
                'children_id' => $children_id,
                'children_type' => $children_type,
            ];
        }
        if (empty($insert_list)) {
            return false;
        }
        return $this->batchInsert($insert_list);
    }

    public function addPowerListByParentIds($parent_ids, $parent_type): array
    {
        if (! in_array($parent_type, self::ENUM_PARENT_TYPE)) {
            throw new RuntimeErrorException('parent_type must in ' . implode(',', self::ENUM_PARENT_TYPE));
        }
        $powerList = $this->getLists([
            'parent_id' => $parent_ids,
            'parent_type' => $parent_type,
        ]);
        $result = [];
        foreach ($powerList as $value) {
            if ($value['children_type'] === self::ENUM_CHILDREN_TYPE_ROLE) {
                $result[$value['parent_id']]['role_ids'][] = $value['children_id'];
            }
            if ($value['children_type'] === self::ENUM_CHILDREN_TYPE_MENU) {
                $result[$value['parent_id']]['menu_ids'][] = $value['children_id'];
            }
            if ($value['children_type'] === self::ENUM_CHILDREN_TYPE_API) {
                $result[$value['parent_id']]['api_ids'][] = $value['children_id'];
            }
        }
        return $result;
    }
}
