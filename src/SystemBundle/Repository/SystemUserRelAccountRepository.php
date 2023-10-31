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
use SystemBundle\Model\SystemUserRelAccountModel;

class SystemUserRelAccountRepository extends Repository
{
    public const ENUM_REL_TYPE_ACCOUNT = 'account';

    public const ENUM_REL_TYPE_EMAIL = 'email';

    public const ENUM_REL_TYPE_MOBILE = 'mobile';

    public const ENUM_REL_TYPE_MINI_APP = 'mini_app';

    public const ENUM_REL_TYPE_OFFICIAL_ACCOUNT = 'official_account';

    public const ENUM_REL_TYPE = [self::ENUM_REL_TYPE_ACCOUNT => 'account', self::ENUM_REL_TYPE_EMAIL => 'email', self::ENUM_REL_TYPE_MOBILE => 'mobile', self::ENUM_REL_TYPE_MINI_APP => 'mini_app', self::ENUM_REL_TYPE_OFFICIAL_ACCOUNT => 'official_account'];

    public const ENUM_REL_TYPE_DEFAULT = self::ENUM_REL_TYPE_ACCOUNT;

    protected SystemUserRelAccountModel $model;

    public function __call($method, $parameters)
    {
        return $this->getModel()->{$method}(...$parameters);
    }

    public function enumRelType(): array
    {
        return self::ENUM_REL_TYPE;
    }

    public function enumRelTypeDefault(): string
    {
        return self::ENUM_REL_TYPE_DEFAULT;
    }

    /**
     * get Model.
     */
    public function getModel(): SystemUserRelAccountModel
    {
        if (empty($this->model)) {
            $this->model = make(SystemUserRelAccountModel::class);
        }
        return $this->model;
    }

    public function checkAccount(string $account): bool
    {
        $info = $this->getInfo(['rel_value' => $account]);
        if (! empty($info)) {
            return false;
        }
        return true;
    }

    public function setColumnData(array $data): array
    {
        if (! empty($data['rel_data'])) {
            $data['rel_data'] = json_encode($data['rel_data']);
        }

        return parent::setColumnData($data);
    }

    public function formatColumnData(array $data): array
    {
        $data = parent::formatColumnData($data);
        if (! empty($data['rel_data'])) {
            $data['rel_data'] = @json_decode($data['rel_data'], true);
        }
        return $data;
    }
}
