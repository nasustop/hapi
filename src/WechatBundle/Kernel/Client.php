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

use ThirdPartyBundle\Kernel\HttpClient;

class Client
{
    public function __construct(protected HttpClient $httpClient, protected AccessToken $accessToken)
    {
    }

    public function request(string $method, string $uri = '', array $options = []): Response
    {
        if ($this->accessToken) {
            $options['query']['access_token'] = $this->accessToken->getToken();
        }
        $response = $this->httpClient->request($method, $uri, $options);
        return new Response($response->getResponse(), $response->getErrors());
    }
}
