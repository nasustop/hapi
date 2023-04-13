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
namespace App\Middleware;

use Nasustop\HapiBase\HttpServer\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RequestStatisticsMiddleware implements MiddlewareInterface
{
    /**
     * @throws \Exception
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $timeBegin = microtime(true);
        try {
            $response = $handler->handle(request: $request);
            $responseContent = $response->getBody()->getContents();
            $responseData = @json_decode($responseContent, true);
            $http_status = $response->getStatusCode();
            $msg_code = (int) ($responseData['code'] ?? 500);
        } catch (\Exception $exception) {
            $http_status = apiResponseHttpStatus($exception);
            $msg_code = apiResponseMsgCode($exception);
            throw $exception;
        } finally {
            $transferTime = round(microtime(true) - $timeBegin, 3);
            $request = container()->get(id: RequestInterface::class);
            $api_alias = $request->getRequestApiAlias();
            $request_ip = $request->getRequestIp();
            $uid = $request->getHeaderLine('uid');
            $uid = $uid ?: (string) generateSnowID();
            if (! empty($api_alias)) {
                $this->apiLog(api_alias: $api_alias, ip: $request_ip, uid: $uid, http_status: $http_status, msg_code: $msg_code, transferTime: $transferTime);
            }
        }

        return $response;
    }

    public function apiLog(string $api_alias, string $ip, string $uid, int $http_status, int $msg_code, float $transferTime)
    {
        $apiData = [
            'timestamp' => time(),
            'api_alias' => $api_alias,
            'ip' => $ip,
            'uid' => $uid,
            'transfer_time' => $transferTime,
            'http_status' => $http_status,
            'msg_code' => $msg_code,
        ];
        go(function () use ($apiData) {
            mongo()->Database('test')->Collection('api_log_' . date('Y-m-d'))->InsertOne($apiData);
        });
    }
}
