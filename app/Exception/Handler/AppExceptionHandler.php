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

namespace App\Exception\Handler;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Logger\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class AppExceptionHandler extends ExceptionHandler
{
    protected LoggerInterface $logger;

    public function __construct(
        protected StdoutLoggerInterface $stdoutLogger,
        protected LoggerFactory $loggerFactory,
        protected FormatterInterface $formatter
    ) {
        $this->logger = $this->loggerFactory->get('default', 'es');
    }

    public function handle(\Throwable $throwable, ResponseInterface $response)
    {
        $response = $response->withAddedHeader('content-type', 'application/json; charset=utf-8');

        $responseData = [
            'code' => apiResponseMsgCode($throwable),
            'msg' => $throwable->getMessage(),
            'data' => [],
        ];

        if (function_exists('sentryException')) {
            sentryException($throwable);
        }
        $responseBody = new SwooleStream(json_encode($responseData));
        if (in_array(config('app_env', 'dev'), ['dev', 'test'])) {
            $responseData['trace'] = explode("\n", $this->formatter->format($throwable));
            $responseBody = new SwooleStream(json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

            $this->stdoutLogger->error(sprintf("%s[%s] in %s\n%s", $throwable->getMessage(), $throwable->getLine(), $throwable->getFile(), $throwable->getTraceAsString()));
        }

        $this->logger->error(sprintf("%s[%s] in %s\n%s", $throwable->getMessage(), $throwable->getLine(), $throwable->getFile(), $throwable->getTraceAsString()));

        return $response->withBody($responseBody);
    }

    public function isValid(\Throwable $throwable): bool
    {
        return true;
    }
}
