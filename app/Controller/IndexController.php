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

use Hyperf\Utils\ApplicationContext;
use Nasustop\HapiSidecar\Example\demo\php\DemoFactory;
use Nasustop\HapiSidecar\Example\mongo\php\MongoFactory;

class IndexController extends AbstractController
{
    public function index()
    {
        $user = $this->getRequest()->input('user', 'Hapi');
        $method = $this->getRequest()->getMethod();

        $demoSidecar = ApplicationContext::getContainer()->get(DemoFactory::class)->get();
        $result = $demoSidecar->Hello('Roc');
        var_dump($result);
        $mongoSidecar = ApplicationContext::getContainer()->get(MongoFactory::class)->get();
        $result = $mongoSidecar->Database('test')->Collection('test')->InsertOne([
            'name' => 'test',
            'age' => 30,
            'sex' => 1,
        ]);
        var_dump($result);

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }
}
