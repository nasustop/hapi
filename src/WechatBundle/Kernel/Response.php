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

use ThirdPartyBundle\Kernel\Exception\BadResponseException;
use Throwable;

class Response extends \ThirdPartyBundle\Kernel\Response
{
    public function toArray()
    {
        if ('' === $content = $this->getContents()) {
            throw new BadResponseException('Request body is empty.');
        }

        $contentType = $this->response->getHeaderLine('content-type');

        if (str_contains($contentType, 'text/xml')
            || str_contains($contentType, 'application/xml')
            || str_starts_with($content, '<xml>')) {
            try {
                $content = Xml::parse($content);
            } catch (Throwable $e) {
                throw new BadResponseException('Response body is not valid xml.', 400, $e);
            }
        } else {
            $content = parent::toArray();
        }

        if (! is_array($content)) {
            throw new BadResponseException(sprintf('Response content was expected to decode to an array, "%s" returned.', get_debug_type($content)));
        }

        return $content;
    }
}
