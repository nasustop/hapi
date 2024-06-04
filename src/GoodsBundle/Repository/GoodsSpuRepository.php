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
use GoodsBundle\Model\GoodsSpuModel;

class GoodsSpuRepository extends Repository
{
    public const ENUM_STATUS_ON_SALE = 'on_sale';

    public const ENUM_STATUS_OFF_SALE = 'off_sale';

    public const ENUM_STATUS = [self::ENUM_STATUS_ON_SALE => 'on_sale', self::ENUM_STATUS_OFF_SALE => 'off_sale'];

    public const ENUM_STATUS_DEFAULT = self::ENUM_STATUS_ON_SALE;

    public const ENUM_SPU_TYPE_NORMAL = 'normal';

    public const ENUM_SPU_TYPE_POINT = 'point';

    public const ENUM_SPU_TYPE_SERVICE = 'service';

    public const ENUM_SPU_TYPE = [self::ENUM_SPU_TYPE_NORMAL => 'normal', self::ENUM_SPU_TYPE_POINT => 'point', self::ENUM_SPU_TYPE_SERVICE => 'service'];

    public const ENUM_SPU_TYPE_DEFAULT = self::ENUM_SPU_TYPE_NORMAL;

    protected GoodsSpuModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    public function enumStatus(): array
    {
        return self::ENUM_STATUS;
    }

    public function enumStatusDefault(): string
    {
        return self::ENUM_STATUS_DEFAULT;
    }

    public function enumSpuType(): array
    {
        return self::ENUM_SPU_TYPE;
    }

    public function enumSpuTypeDefault(): string
    {
        return self::ENUM_SPU_TYPE_DEFAULT;
    }

    /**
     * get Model.
     */
    public function getModel(): GoodsSpuModel
    {
        if (empty($this->model)) {
            $this->model = make(GoodsSpuModel::class);
        }
        return $this->model;
    }

    public function formatColumnData(array $data): array
    {
        $data = parent::setColumnData($data);
        foreach ($data as $key => $value) {
            if ($key === 'spu_images') {
                $data[$key] = json_decode($value, true);
            }
            switch ($key) {
                case 'spu_images':
                    $data[$key] = json_decode($value, true);
                    break;
                case 'open_params':
                case 'open_spec':
                    $data[$key] = $value === 1;
                    break;
            }
        }
        return $data;
    }

    public function setColumnData(array $data): array
    {
        $data = parent::setColumnData($data);
        foreach ($data as $key => $value) {
            if ($key === 'spu_images') {
                $data[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);
            }
        }
        return $data;
    }

    public function pageListsByJoin($filter, $columns = '*', $page = 1, $pageSize = 20, $orderBy = [])
    {
        $query = $this->findQuery();
        if (! empty($filter['category_id'])) {
            $category_id = $filter['category_id'];
            unset($filter['category_id']);
            $table = $this->getModel()->getTable();
            $relRepository = make(GoodsSpuRelValueRepository::class);
            $relTable = $relRepository->getModel()->getTable();
            $query->leftJoin($relTable, $table . '.spu_id', '=', $relTable . '.spu_id')
                ->where([
                    $relTable . '.rel_type' => GoodsSpuRelValueRepository::ENUM_REL_TYPE_CATEGORY,
                    $relTable . '.rel_id' => $category_id,
                ]);
            foreach ($filter as $key => $value) {
                if (is_string($key) && ! str_contains($key, '.')) {
                    $filter[$key] = $table . '.' . $value;
                }
            }
            foreach ($orderBy as $key => $value) {
                if (is_string($key) && ! str_contains($key, '.')) {
                    $orderBy[$key] = $table . '.' . $value;
                }
            }
            if ($columns === '*') {
                $columns = $table . '.' . $columns;
            }
        }
        $query = $this->_filter($query, $filter);
        $columns = $this->_columns($columns);
        $query = $query->select($columns);
        $countQuery = $query->clone();
        $count = $countQuery->count();
        if ($page > 0 && $pageSize > 0) {
            $query = $query->offset(($page - 1) * $pageSize)
                ->limit($pageSize);
        }
        foreach ($orderBy as $key => $value) {
            $query = $query->orderBy($key, $value);
        }
        $result['total'] = $count;
        $result['list'] = $query->get()->toArray();
        foreach ($result['list'] as $key => $value) {
            $result['list'][$key] = $this->formatColumnData($value);
        }
        return $result;
    }
}
