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
namespace WechatBundle\Kernel;

use Hyperf\Utils\Arr;
use InvalidArgumentException;

class Config
{
    protected array $requiredKeys = [];

    public function __construct(protected array $items)
    {
        $this->checkMissingKeys();
    }

    public function get(string $key, $default = null)
    {
        return Arr::get(array: $this->items, key: $key, default: $default);
    }

    public function set(string $key, mixed $value): self
    {
        Arr::set(array: $this->items, key: $key, value: $value);
        return $this;
    }

    public function all(): array
    {
        return $this->items;
    }

    public function has(string $key): bool
    {
        return Arr::has(array: $this->items, keys: $key);
    }

    public function checkMissingKeys(): bool
    {
        if (empty($this->requiredKeys)) {
            return true;
        }

        $missingKeys = [];

        foreach ($this->requiredKeys as $key) {
            if (! $this->has($key)) {
                $missingKeys[] = $key;
            }
        }

        if (! empty($missingKeys)) {
            throw new InvalidArgumentException(sprintf("\"%s\" cannot be empty.\r\n", implode(',', $missingKeys)));
        }

        return true;
    }
}
