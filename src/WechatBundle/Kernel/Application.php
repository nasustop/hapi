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

use Psr\SimpleCache\CacheInterface;
use ThirdPartyBundle\Kernel\HttpClient;

abstract class Application
{
    protected Config $config;

    protected CacheInterface $cache;

    protected Account $account;

    protected AccessToken $accessToken;

    protected HttpClient $httpClient;

    protected Client $client;

    public function __construct(array|Config $config)
    {
        $this->config = is_array($config) ? new Config($config) : $config;
    }

    /**
     * get Config.
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * set Config.
     */
    public function setConfig(Config $config): self
    {
        $this->config = $config;

        return $this;
    }

    /**
     * get Cache.
     */
    public function getCache(): CacheInterface
    {
        if (empty($this->cache)) {
            $this->cache = cache();
        }
        return $this->cache;
    }

    /**
     * set Cache.
     */
    public function setCache(CacheInterface $cache): self
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * get Account.
     */
    public function getAccount(): Account
    {
        if (empty($this->account)) {
            $this->account = new Account(app_id: (string) $this->config->get('app_id'), secret: (string) $this->config->get('secret'));
        }
        return $this->account;
    }

    /**
     * set Account.
     */
    public function setAccount(Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    /**
     * get AccessToken.
     */
    public function getAccessToken(): AccessToken
    {
        if (empty($this->accessToken)) {
            $this->accessToken = new AccessToken(
                account: $this->getAccount(),
                cache: $this->getCache(),
                httpClient: $this->getHttpClient(),
            );
        }
        return $this->accessToken;
    }

    /**
     * set AccessToken.
     */
    public function setAccessToken(AccessToken $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * get HttpClient.
     */
    public function getHttpClient(): HttpClient
    {
        if (empty($this->httpClient)) {
            $this->httpClient = new HttpClient(options: [
                'base_uri' => 'https://api.weixin.qq.com',
            ]);
        }
        return $this->httpClient;
    }

    /**
     * set HttpClient.
     */
    public function setHttpClient(HttpClient $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * get Client.
     */
    public function getClient(): Client
    {
        if (empty($this->client)) {
            $this->client = new Client(httpClient: $this->getHttpClient(), accessToken: $this->getAccessToken());
        }
        return $this->client;
    }

    /**
     * set Client.
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
