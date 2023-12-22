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

namespace App\Crontab;

class DemoCrontab
{
    public function execute()
    {
        var_dump('执行时间：' . date('Y-m-d H:i:s'));
        $startTime = microtime(true);
        $num = 100000;
        $sum = 0;
        for ($i = 0; $i < $num; ++$i) {
            for ($j = 0; $j < $num; ++$j) {
                ++$sum;
            }
        }
        $runtime = round(microtime(true) - $startTime, 3);
        var_dump('程序运行时间：' . $runtime);
    }
}
