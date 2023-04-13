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
namespace ThirdPartyBundle\Kernel;

use App\Exception\HttpClientBadRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Hyperf\Guzzle\ClientFactory;
use ThirdPartyBundle\Event\HttpRequestEvent;

class HttpClient
{
    protected ClientFactory $clientFactory;

    protected Client $client;

    protected Response $response;

    public function __construct(protected array $options = [])
    {
    }

    public function __call($method, $parameters)
    {
        return $this->getClient()->{$method}(...$parameters);
    }

    /**
     * set Config.
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * get ClientFactory.
     */
    public function getClientFactory(): ClientFactory
    {
        if (empty($this->clientFactory)) {
            $this->clientFactory = make(name: ClientFactory::class);
        }
        return $this->clientFactory;
    }

    /**
     * get Client.
     */
    public function getClient(): Client
    {
        return $this->getClientFactory()->create(options: $this->options);
    }

    public function get(string $uri, array $options = []): Response
    {
        return $this->request(
            method: 'get',
            uri: $uri,
            options: $options,
        );
    }

    public function post(string $uri, array $options = []): Response
    {
        return $this->request(
            method: 'post',
            uri: $uri,
            options: $options,
        );
    }

    public function request(string $method, string $uri = '', array $options = []): Response
    {
        if (! isset($options['on_stats'])) {
            $options['on_stats'] = $this->onStats(options: $options);
        }
        try {
            $response = $this->getClient()->request(
                method: $method,
                uri: $uri,
                options: $options,
            );
        } catch (\Throwable $exception) {
            $error = new HttpClientBadRequestException(message: $exception->getMessage(), previous: $exception);
        }
        return new Response(response: $response ?? null, error: $error ?? null);
    }

    protected function onStats(array $options = []): \Closure
    {
        return function (TransferStats $stats) use ($options) {
            $method = $stats->getRequest()->getMethod();
            $scheme = $stats->getRequest()->getUri()->getScheme();
            $host = $stats->getRequest()->getUri()->getHost();
            $port = $stats->getRequest()->getUri()->getPort();
            $path = $stats->getRequest()->getUri()->getPath();
            $headers = $stats->getRequest()->getHeaders();
            $query = $stats->getRequest()->getUri()->getQuery();
            $body = $stats->getRequest()->getBody()->getContents();
            $transfer_time = $stats->getTransferTime();
            $status_code = $stats->getResponse()->getStatusCode();
            $response = $stats->getResponse();
            // getContents 返回剩余的内容，因此第二次调用不会返回任何内容，除非您使用 rewind 或 seek 查找流的位置
            $stats->getRequest()->getBody()->rewind();
            $stats->getResponse()->getBody()->rewind();
            $queryDecode = [];
            parse_str($query, $queryDecode);
            $responseObject = new Response(response: $response, error: null);
            $responseArray = $responseObject->toArray();
            $event = new HttpRequestEvent(
                method: $method,
                scheme: $scheme,
                host: $host,
                port: $port,
                path: $path,
                headers: $headers,
                query: $queryDecode,
                body: $body,
                options: $options,
                transfer_time: $transfer_time,
                status_code: $status_code,
                response: $responseArray,
            );
            event(event: $event);
        };
    }
}
