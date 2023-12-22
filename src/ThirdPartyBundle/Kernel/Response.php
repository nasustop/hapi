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
use App\Exception\HttpClientBadResponseException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @method getStatusCode()
 * @method withStatus($code, $reasonPhrase = '')
 * @method getReasonPhrase()
 * @method getProtocolVersion()
 * @method withProtocolVersion($version)
 * @method getHeaders()
 * @method hasHeader($name)
 * @method getHeader($name)
 * @method getHeaderLine($name)
 * @method withHeader($name, $value)
 * @method withAddedHeader($name, $value)
 * @method withoutHeader($name)
 * @method getBody()
 * @method withBody(StreamInterface $body)
 */
class Response
{
    public function __construct(protected ?ResponseInterface $response, protected ?HttpClientBadRequestException $error)
    {
        if (empty($this->response) && empty($this->error)) {
            throw new HttpClientBadRequestException(message: 'Response and error cannot be null at the same time');
        }
    }

    public function __call(string $name, array $arguments): mixed
    {
        if (empty($this->response)) {
            throw $this->getErrors();
        }
        return $this->response->{$name}(...$arguments);
    }

    public function __toString(): string
    {
        return $this->toJson() ?: '';
    }

    /**
     * get Response.
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    public function hasErrors(): bool
    {
        return ! empty($this->error);
    }

    public function getErrors(): ?HttpClientBadRequestException
    {
        return $this->error;
    }

    public function getContents(): string
    {
        if (empty($this->response) || $this->hasErrors()) {
            throw $this->getErrors();
        }
        $contents = $this->response->getBody()->getContents();
        $this->response->getBody()->rewind();
        return $contents;
    }

    /**
     * 返回数据必须为json格式的数据.
     */
    public function toArray()
    {
        if ('' === $content = $this->getContents()) {
            throw new HttpClientBadResponseException(message: 'Request body is empty.');
        }

        try {
            $content = json_decode($content, true, 512, JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR);
        } catch (\Throwable $e) {
            throw new HttpClientBadResponseException(message: 'Could not json decode request body.', previous: $e);
        }

        if (! is_array($content)) {
            throw new HttpClientBadResponseException(sprintf('Response content was expected to decode to an array, "%s" returned.', get_debug_type($content)));
        }

        return $content;
    }

    public function toJson(): false|string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toDataUrl(): string
    {
        return 'data:' . $this->response->getHeaderLine('content-type') . ';base64,' . base64_encode($this->getContents());
    }

    public function is(string $type): bool
    {
        $contentType = $this->response->getHeaderLine('content-type');

        return match (strtolower($type)) {
            'json' => str_contains($contentType, '/json'),
            'xml' => str_contains($contentType, '/xml'),
            'html' => str_contains($contentType, '/html'),
            'image' => str_contains($contentType, 'image/'),
            'audio' => str_contains($contentType, 'audio/'),
            'video' => str_contains($contentType, 'video/'),
            'text' => str_contains($contentType, 'text/')
                || str_contains($contentType, '/json')
                || str_contains($contentType, '/xml'),
            default => false,
        };
    }
}
