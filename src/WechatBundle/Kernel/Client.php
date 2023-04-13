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

use GuzzleHttp\TransferStats;
use Swoole\Http\Status;
use ThirdPartyBundle\Event\HttpRequestEvent;
use ThirdPartyBundle\Kernel\HttpClient;
use ThirdPartyBundle\Kernel\Response;

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
        $options['on_stats'] = $this->onStats(options: $options);
        $response = $this->httpClient->request(method: $method, uri: $uri, options: $options);
        return new Response(response: $response->getResponse(), error: $response->getErrors());
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
            $status = $status_code == Status::OK ? 'success' : 'fail';
            if (! empty($responseArray['errcode'])) {
                $status = 'fail';
            }
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
                status: $status,
            );
            event(event: $event);
        };
    }
}
