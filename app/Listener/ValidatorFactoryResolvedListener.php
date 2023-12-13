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

namespace App\Listener;

use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\Event\ValidatorFactoryResolved;

#[Listener]
class ValidatorFactoryResolvedListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            ValidatorFactoryResolved::class,
        ];
    }

    public function process(object $event): void
    {
        /** @var ValidatorFactoryInterface $validatorFactory */
        $validatorFactory = $event->validatorFactory;
        $this->registerSystemUserRelAccount($validatorFactory);
    }

    public function validStringType(string $valid, int $useNum = 3, bool $useLower = true, bool $useUpper = true, bool $useNumber = true, bool $useChar = true)
    {
        $lowercase = [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
            'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        ];
        $uppercase = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
            'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        ];
        $number = [
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        ];
        $character = [
            '!', '@', '#', '$', '%', '&', '*', '-', '+', '=', '?', '.',
        ];
        $msg = '';
        if ($useLower) {
            $msg .= '小写字母';
        }
        if ($useUpper) {
            $msg .= '大写字母';
        }
        if ($useNumber) {
            $msg .= '数字';
        }
        if ($useChar) {
            $msg .= '特殊字符[!@#$%&*-+=?.]';
        }
        $data = str_split($valid);
        $validType = [];
        foreach ($data as $char) {
            if ($useLower && in_array($char, $lowercase)) {
                $validType[] = 'lower';
                continue;
            }
            if ($useUpper && in_array($char, $uppercase)) {
                $validType[] = 'upper';
                continue;
            }
            if ($useNumber && in_array($char, $number)) {
                $validType[] = 'number';
                continue;
            }
            if ($useChar && in_array($char, $character)) {
                $validType[] = 'char';
                continue;
            }
            throw new BadRequestHttpException('只能是' . $msg . '的组合');
        }
        $validType = array_unique($validType);
        if (count($validType) < $useNum) {
            throw new BadRequestHttpException('至少要有' . $msg . '中的' . $useNum . '种');
        }
    }

    /**
     * 用户账号唯一性验证.
     */
    protected function registerSystemUserRelAccount(ValidatorFactoryInterface $validatorFactory)
    {
        // 注册验证器
        $validatorFactory->extend('checkAccount', function ($attribute, $value, $parameters, $validator) {
            if (empty($value)) {
                return true;
            }
            $length = mb_strlen($value);
            if ($length < 6 || $length > 18) {
                throw new BadRequestHttpException('账号长度应为6到18位');
            }
            try {
                $this->validStringType($value, 1, true, true, true, false);
            } catch (\Exception $e) {
                throw new BadRequestHttpException('账号' . $e->getMessage());
            }
            return true;
        });
        $validatorFactory->extend('checkPassword', function ($attribute, $value, $parameters, $validator) {
            if (empty($value)) {
                return true;
            }
            $length = mb_strlen($value);
            if ($length < 6 || $length > 18) {
                throw new BadRequestHttpException('密码长度应为6到18位');
            }
            try {
                $this->validStringType($value);
            } catch (\Exception $e) {
                throw new BadRequestHttpException('密码' . $e->getMessage());
            }
            return true;
        });
    }
}
