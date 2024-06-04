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

use Hyperf\Context\ApplicationContext;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\View\RenderInterface;
use Nasustop\HapiBase\HttpServer\RequestInterface;
use Nasustop\HapiBase\HttpServer\ResponseInterface;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    private ContainerInterface $container;

    private RequestInterface $request;

    private ResponseInterface $response;

    private ValidatorFactoryInterface $validatorFactory;

    private RenderInterface $render;

    /**
     * get Container.
     */
    public function getContainer(): ContainerInterface
    {
        if (empty($this->container)) {
            $this->container = ApplicationContext::getContainer();
        }
        return $this->container;
    }

    /**
     * get Request.
     */
    public function getRequest(): RequestInterface
    {
        if (empty($this->request)) {
            $this->request = make(RequestInterface::class);
        }
        return $this->request;
    }

    /**
     * get Response.
     */
    public function getResponse(): ResponseInterface
    {
        if (empty($this->response)) {
            $this->response = make(ResponseInterface::class);
        }
        return $this->response;
    }

    /**
     * get ValidatorFactory.
     */
    public function getValidatorFactory(): ValidatorFactoryInterface
    {
        if (empty($this->validatorFactory)) {
            $this->validatorFactory = make(ValidatorFactoryInterface::class);
        }
        return $this->validatorFactory;
    }

    /**
     * get Render.
     */
    public function getRender(): RenderInterface
    {
        if (empty($this->render)) {
            $this->render = make(RenderInterface::class);
        }
        return $this->render;
    }
}
