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

namespace App\Job;

use Hyperf\Contract\ApplicationInterface;
use Nasustop\HapiQueue\Job\Job;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class InstallJob extends Job
{
    public function handle(): string
    {
        $output = new NullOutput();
        /** @var Application $application */
        $application = make(ApplicationInterface::class);
        $application->setAutoExit(false);

        // 数据库迁移
        $params = ['command' => 'migrate'];
        $input = new ArrayInput($params);
        $exitCode = $application->run($input, $output);

        // 加载API路由
        $params = ['command' => 'hapi:system:api'];
        $input = new ArrayInput($params);
        $exitCode = $application->run($input, $output);

        // 导入菜单
        $params = ['command' => 'hapi:system:menu', '--action' => 'upload'];
        $input = new ArrayInput($params);
        $exitCode = $application->run($input, $output);

        $key = 'SYSTEM_INSTALL_STATUS';
        redis()->set($key, 'success');
        return self::ACK;
    }
}
