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
use SystemBundle\Model\SystemOperationLogModel;

class SystemOperationLogRepository extends Repository
{
    protected SystemOperationLogModel $model;

    protected SystemUserRepository $systemUserRepository;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): SystemOperationLogModel
    {
        if (empty($this->model)) {
            $this->model = container()->get(SystemOperationLogModel::class);
        }
        return $this->model;
    }

    public function getSystemUserRepository(): SystemUserRepository
    {
        if (empty($this->systemUserRepository)) {
            $this->systemUserRepository = make(SystemUserRepository::class);
        }
        return $this->systemUserRepository;
    }

    public function getLists(array $filter = [], array|string $columns = '*', int $page = 0, int $pageSize = 0, array $orderBy = []): array
    {
        $list = parent::getLists($filter, $columns, $page, $pageSize, $orderBy);
        $userIds = array_values(array_filter(array_unique(array_column($list, 'user_id'))));
        $userList = ! empty($userIds) ? $this->getSystemUserRepository()->getLists(['user_id' => $userIds]) : [];
        $userList = array_column($userList, null, 'user_id');
        foreach ($list as $key => $value) {
            $list[$key]['user_mobile'] = $userList[$value['user_id']]['mobile'] ?? '————';
            if ($value['api_alias'] == 'app.system.login') {
                $list[$key]['params'] = '{}';
            }
        }
        return $list;
    }

    public function setColumnData(array $data): array
    {
        $data = parent::setColumnData($data);
        foreach ($data as $key => $value) {
            if ($key === 'params') {
                $data[$key] = json_encode($value);
            }
        }
        return $data;
    }

    public function formatColumnData(array $data): array
    {
        $data = parent::formatColumnData($data);
        foreach ($data as $key => $value) {
            if ($key === 'params') {
                $data[$key] = ! empty($value) ? @json_decode($value, true) : $value;
            }
        }
        return $data;
    }
}
