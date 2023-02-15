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
namespace ThirdPartyBundle\Factory;

use ThirdPartyBundle\Kernel\HttpClient;

class HttpClientFactory
{
    protected HttpClient $client;

    public function __construct(protected array $options = [])
    {
    }

    /**
     * set Options.
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }

    /**
     * get Client.
     */
    public function getClient(): HttpClient
    {
        return new HttpClient(options: $this->options);
    }
}
