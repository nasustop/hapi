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

namespace ThirdPartyBundle\Event;

use Nasustop\HapiQueue\Job\Job;
use Swoole\Http\Status;
use ThirdPartyBundle\Service\ThirdPartyRequestLogService;

class HttpRequestEvent extends Job
{
    public function __construct(
        protected string $method,
        protected string $scheme,
        protected string $host,
        protected ?int $port,
        protected string $path,
        protected array $headers,
        protected array $query,
        protected string $body,
        protected array $options,
        protected float $transfer_time,
        protected int $status_code,
        protected array $response,
        protected string $status = '',
    ) {}

    public function handle(): string
    {
        $host = $this->scheme . '://' . $this->host;
        if (! empty($this->port)) {
            $host .= ':' . $this->port;
        }
        if (! empty($this->options['query'])) {
            $this->options['query'] = array_replace($this->options['query'], $this->query);
        } else {
            $this->options['query'] = $this->query;
        }
        if (empty($this->status) || ! in_array($this->status, ['success', 'fail'])) {
            $this->status = $this->status_code == Status::OK ? 'success' : 'fail';
        }
        $saveData = [
            'method' => $this->method,
            'host' => $host,
            'path' => $this->path,
            'params' => $this->options,
            'transfer_time' => $this->transfer_time,
            'status_code' => $this->status_code,
            'status' => $this->status,
            'result' => $this->response,
        ];
        $service = make(name: ThirdPartyRequestLogService::class);
        $service->getRepository()->saveData(data: $saveData);
        return self::ACK;
    }
}
