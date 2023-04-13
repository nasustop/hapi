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
namespace App\Controller;

use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Nasustop\HapiBase\HttpServer\RequestInterface;
use Nasustop\HapiBase\HttpServer\ResponseInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    private ContainerInterface $container;

    private RequestInterface $request;

    private ResponseInterface $response;

    private ValidatorFactoryInterface $validatorFactory;

    /**
     * get Container.
     */
    public function getContainer(): ContainerInterface
    {
        if (empty($this->container)) {
            $this->container = container();
        }
        return $this->container;
    }

    /**
     * get Request.
     */
    public function getRequest(): RequestInterface
    {
        if (empty($this->request)) {
            $this->request = $this->getContainer()->get(RequestInterface::class);
        }
        return $this->request;
    }

    /**
     * get Response.
     */
    public function getResponse(): ResponseInterface
    {
        if (empty($this->response)) {
            $this->response = $this->getContainer()->get(ResponseInterface::class);
        }
        return $this->response;
    }

    /**
     * get ValidatorFactory.
     */
    public function getValidatorFactory(): ValidatorFactoryInterface
    {
        if (empty($this->validatorFactory)) {
            $this->validatorFactory = $this->getContainer()->get(ValidatorFactoryInterface::class);
        }
        return $this->validatorFactory;
    }
}
