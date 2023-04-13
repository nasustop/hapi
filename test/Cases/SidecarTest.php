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
namespace HyperfTest\Cases;

use Nasustop\HapiSidecar\IPC\SocketIPCSender;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class SidecarTest extends TestCase
{
    public function testSend()
    {
        go(function () {
            var_dump(config('sidecar.socket_address'));
            $task = new SocketIPCSender(config('sidecar.socket_address'));
            $result = $task->call('DemoService.Hello', json_encode(['name' => 'sidecar', 'value' => 'hello']));
            var_dump($result);
        });
    }
}
