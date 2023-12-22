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

use App\Exception\HttpClientBadRequestException;
use Psr\SimpleCache\CacheInterface;
use ThirdPartyBundle\Kernel\HttpClient;

class AccessToken
{
    public function __construct(
        protected Account $account,
        protected ?string $key = null,
        protected ?CacheInterface $cache = null,
        protected ?HttpClient $httpClient = null,
    ) {
        if (empty($this->cache)) {
            $this->cache = cache();
        }
        if (empty($this->httpClient)) {
            $this->httpClient = new HttpClient(options: ['base_uri' => 'https://api.weixin.qq.com']);
        }
    }

    /**
     * get cache key.
     */
    public function getKey(): string
    {
        return $this->key ?? $this->key = sprintf('wechat.mini_app.access_token.%s.%s', $this->account->getAppId(), $this->account->getSecret());
    }

    /**
     * set cache key.
     */
    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function getToken(): string
    {
        $token = $this->cache->get(key: $this->getKey());

        if ((bool) $token && is_string($token)) {
            return $token;
        }

        return $this->refresh();
    }

    public function refresh()
    {
        $response = $this->httpClient->request(
            method: 'GET',
            uri: '/cgi-bin/token',
            options: [
                'query' => [
                    'grant_type' => 'client_credential',
                    'appid' => $this->account->getAppId(),
                    'secret' => $this->account->getSecret(),
                ],
            ]
        )->toArray();

        if (empty($response['access_token'])) {
            throw new HttpClientBadRequestException('Failed to get access_token: ' . json_encode($response, JSON_UNESCAPED_UNICODE));
        }

        $ttl = intval($response['expires_in']) > 5 ? intval($response['expires_in']) - 5 : 0;
        if (! empty($ttl)) {
            $this->cache->set(key: $this->getKey(), value: $response['access_token'], ttl: $ttl);
        }

        return $response['access_token'];
    }
}
