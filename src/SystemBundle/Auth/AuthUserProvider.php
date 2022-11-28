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

use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Hyperf\HttpMessage\Exception\UnauthorizedHttpException;
use Hyperf\Snowflake\IdGeneratorInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Nasustop\HapiBase\Auth\UserProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\SimpleCache\InvalidArgumentException;
use SystemBundle\Service\SystemUserService;

class AuthUserProvider extends UserProvider
{
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

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     */
    public function login(array $inputData): array
    {
        $validatorFactory = $this->container->get(ValidatorFactoryInterface::class);
        $validator = $validatorFactory->make($inputData, [
            'user_name' => 'required',
            'password' => 'required',
        ], [
            'user_name.*' => '请填写登录账号',
            'password.*' => '请填写密码',
        ]);
        if ($validator->fails()) {
            throw new BadRequestHttpException($validator->errors()->first());
        }
        $service = $this->container->get(SystemUserService::class);
        $userInfo = $service->getRepository()->getInfo([
            [
                ['login_name' => $inputData['user_name']],
                'or',
                ['mobile' => $inputData['user_name']],
            ],
        ]);
        if (empty($userInfo)) {
            throw new BadRequestHttpException('账号不存在');
        }
        // 验证密码
        if (! $service->getRepository()->validatePassword($inputData['password'], $userInfo['password'])) {
            throw new BadRequestHttpException('密码错误');
        }
        unset($userInfo['password']);
        // 验证状态
        $service->getRepository()->validateUserStatus($userInfo['user_status']);

        // support admin user
        $support_admin_user = config(sprintf('auth.%s.support_admin_user', $this->guard), '');
        $support_admin_user = explode(',', $support_admin_user);
        $userInfo['is_support_user'] = ! empty($userInfo['user_id']) && in_array($userInfo['user_id'], $support_admin_user);

        $cacheUserIdKey = $this->getCacheUserIdKey($userInfo['user_id']);
        $cacheSnowflakeId = cache($this->getCacheDriver())->get($cacheUserIdKey);
        if (empty($cacheSnowflakeId)) {
            // generate snowflake id
            $generator = $this->container->get(IdGeneratorInterface::class);
            $cacheSnowflakeId = $generator->generate();
        }

        $exp = (int) config(sprintf('auth.%s.jwt.exp', $this->guard), 7200);
        cache($this->getCacheDriver())->set((string) $cacheSnowflakeId, $userInfo, config(sprintf('auth.%s.jwt.exp', $this->guard), $exp));
        cache($this->getCacheDriver())->set($cacheUserIdKey, $cacheSnowflakeId, $exp);

        return ['id' => $cacheSnowflakeId];
    }

    /**
     * @throws InvalidArgumentException
     */
    public function logout(array $payload): bool
    {
        if (! empty($payload['id'])) {
            $userInfo = cache($this->getCacheDriver())->get((string) $payload['id']);
            if (! empty($userInfo['user_id'])) {
                $cacheUserIdKey = $this->getCacheUserIdKey($userInfo['user_id']);
                cache($this->getCacheDriver())->delete($cacheUserIdKey);
            }
            cache($this->getCacheDriver())->delete((string) $payload['id']);
        }
        return true;
    }

    protected function getCacheDriver()
    {
        return config(sprintf('auth.%s.cache', $this->guard), 'default');
    }

    protected function getCacheUserIdKey($user_id): string
    {
        return sprintf('auth:user_id:%s', $user_id);
    }
}
