<?php

declare(strict_types=1);
/**
 * This file is part of HapiBase.
 *
 * @link     https://www.nasus.top
 * @document https://wiki.nasus.top
 * @contact  xupengfei@xupengfei.net
 * @license  https://github.com/nasustop/hapi/blob/master/LICENSE
 */
namespace App\Exception\Handler;

use App\Constants\ErrorCode;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Hyperf\HttpMessage\Base\Response;
use Hyperf\HttpMessage\Exception\HttpException;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    protected LoggerInterface $logger;

    public function __construct(
        protected StdoutLoggerInterface $stdoutLogger,
        protected LoggerFactory $loggerFactory,
        protected FormatterInterface $formatter
    ) {
        $this->logger = $this->loggerFactory->get('default');
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $response = $response->withAddedHeader('content-type', 'application/json; charset=utf-8');
        $code = $throwable->getCode();
        if (empty($code) && $throwable instanceof HttpException) {
            $code = $throwable->getStatusCode();
        }
        if (empty($code)) {
            $code = ErrorCode::SERVER_ERROR;
        }

        $responseData = [
            'code' => $code,
            'msg' => $throwable->getMessage(),
            'data' => [],
        ];

        $responseBody = new SwooleStream(json_encode($responseData));
        if (in_array(ApplicationContext::getContainer()->get(ConfigInterface::class)->get('app_env', 'dev'), ['dev', 'test'])) {
            $responseData['trace'] = explode("\n", $this->formatter->format($throwable));
            $responseBody = new SwooleStream(json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        }

        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());
        $this->stdoutLogger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->stdoutLogger->error($throwable->getTraceAsString());

        if (! empty(Response::getReasonPhraseByCode($code))) {
            $response = $response->withStatus($code);
        }
        return $response->withBody($responseBody);
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
