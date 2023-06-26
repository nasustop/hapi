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
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use SystemBundle\Model\SystemUserModel;

class SystemUserRepository extends Repository
{
    protected SystemUserModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    /**
     * get Model.
     */
    public function getModel(): SystemUserModel
    {
        if (empty($this->model)) {
            $this->model = make(SystemUserModel::class);
        }
        return $this->model;
    }

    public function generatePassword($pwd): string
    {
        return password_hash($pwd, PASSWORD_DEFAULT);
    }

    public function validatePassword($pwd, $hash): bool
    {
        return password_verify($pwd, $hash);
    }

    public function validateUserStatus($user_status)
    {
        if ($user_status == 'disabled') {
            throw new BadRequestHttpException('账号已被禁用，请联系管理员');
        }
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
            if ($key === 'password') {
                $value = $this->generatePassword($value);
            }
            $result[$key] = $value;
        }

        return $result;
    }
}
