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

namespace SystemBundle\Auth;

use Hyperf\Contract\ConfigInterface;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Hyperf\HttpMessage\Exception\UnauthorizedHttpException;
use Hyperf\Snowflake\IdGeneratorInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Nasustop\HapiAuth\UserProvider;
use Psr\SimpleCache\InvalidArgumentException;
use SystemBundle\Service\SystemUserService;

class AuthUserProvider extends UserProvider
{
    protected ValidatorFactoryInterface $validatorFactory;

    protected SystemUserService $service;

    protected ConfigInterface $config;

    public function getInfo(array $payload): array
    {
        if (empty($payload['id'])) {
            throw new UnauthorizedHttpException('登录失效，请重新登录');
        }
        $user = cache($this->getCacheDriver())->get((string) $payload['id']);
        if (empty($user)) {
            throw new UnauthorizedHttpException('登录失效，请重新登录');
        }
        $user['id'] = $payload['id'];
        return $user;
    }

    public function login(array $inputData): array
    {
        $validator = $this->getValidatorFactory()->make($inputData, [
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.*' => '请填写账号',
            'password.*' => '请填写密码',
        ]);
        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->errors()->first());
        }
        $relData = $this->getService()->getSystemUserRelAccountRepository()->getInfo([
            'rel_key' => $inputData['username'],
        ]);
        if (empty($relData)) {
            throw new BadRequestHttpException('账号不存在');
        }
        $userInfo = $this->getService()->getRepository()->getInfo([
            'user_id' => $relData['user_id'],
        ]);
        if (empty($userInfo)) {
            $this->getService()->getSystemUserRelAccountRepository()
                ->deleteBy(['user_id' => $relData['user_id']]);
            throw new BadRequestHttpException('账号不存在');
        }
        // 验证密码
        if (! $this->getService()->getRepository()->validatePassword($inputData['password'], $userInfo['password'], $userInfo['password_hash'])) {
            throw new BadRequestHttpException('密码错误');
        }
        unset($userInfo['password']);
        // 验证状态
        $this->getService()->getRepository()->validateUserStatus(user_status: $userInfo['user_status']);

        // support admin user
        $support_admin_user = config(sprintf('auth.%s.support_admin_user', $this->guard), '');
        $support_admin_user = explode(',', $support_admin_user);
        $userInfo['is_support_user'] = ! empty($userInfo['user_id']) && in_array($userInfo['user_id'], $support_admin_user);

        $cacheUserIdKey = $this->getCacheUserIdKey(user_id: $userInfo['user_id']);
        $cacheSnowflakeId = cache($this->getCacheDriver())->get(key: $cacheUserIdKey);
        if (empty($cacheSnowflakeId)) {
            /** @var IdGeneratorInterface $idGenerator */
            $idGenerator = make(IdGeneratorInterface::class);
            $cacheSnowflakeId = $idGenerator->generate();
        }

        $exp = (int) config(sprintf('auth.%s.jwt.exp', $this->guard), 7200);
        cache($this->getCacheDriver())->set(key: (string) $cacheSnowflakeId, value: $userInfo, ttl: $exp);
        cache($this->getCacheDriver())->set(key: $cacheUserIdKey, value: $cacheSnowflakeId, ttl: $exp);

        return ['id' => $cacheSnowflakeId];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function logout(array $payload): bool
    {
        if (! empty($payload['id'])) {
            $userInfo = cache($this->getCacheDriver())->get(key: (string) $payload['id']);
            if (! empty($userInfo['user_id'])) {
                $cacheUserIdKey = $this->getCacheUserIdKey(user_id: $userInfo['user_id']);
                cache($this->getCacheDriver())->delete(key: $cacheUserIdKey);
            }
            cache($this->getCacheDriver())->delete(key: (string) $payload['id']);
        }
        return true;
    }

    public function validateToken(array $payload): array
    {
        if (empty($payload['id'])) {
            return [];
        }
        $user = cache($this->getCacheDriver())->get(key: (string) $payload['id']);
        if (empty($user)) {
            return [];
        }
        $user['id'] = $payload['id'];
        return $user;
    }

    protected function getService(): SystemUserService
    {
        if (empty($this->service)) {
            $this->service = make(SystemUserService::class);
        }
        return $this->service;
    }

    protected function getValidatorFactory(): ValidatorFactoryInterface
    {
        if (empty($this->validatorFactory)) {
            $this->validatorFactory = make(ValidatorFactoryInterface::class);
        }
        return $this->validatorFactory;
    }

    protected function getCacheDriver()
    {
        return $this->getConfig()->get(sprintf('auth.%s.cache', $this->guard), 'default');
    }

    protected function getCacheUserIdKey(int $user_id): string
    {
        return sprintf('auth:user_id:%s', $user_id);
    }

    protected function getConfig(): ConfigInterface
    {
        if (empty($this->config)) {
            $this->config = make(ConfigInterface::class);
        }
        return $this->config;
    }
}
