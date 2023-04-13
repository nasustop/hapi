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
namespace SystemBundle\Service\UploadFile;

use App\Exception\RuntimeErrorException;
use SystemBundle\Basic\BasicUploadFileService;

class DemoUploadFileService extends BasicUploadFileService
{
    protected bool $is_batch_handle = true;

    public function getHeaderTitle(): array
    {
        return [
            'name' => ['name' => '名称', 'type' => 'string', 'is_need' => true, 'remarks' => '名称必填'],
            'sex' => ['name' => '性别', 'type' => 'string', 'is_need' => true, 'remarks' => '性别必填'],
            'age' => ['name' => '年龄', 'type' => 'int', 'is_need' => false, 'remarks' => '年龄非必填'],
            'card_id' => ['name' => '身份证号', 'type' => 'int', 'is_need' => true, 'remarks' => '身份证号必填'],
        ];
    }

    public function handle(int $file_rel_id, array $rawData)
    {
        // TODO: Implement handle() method.
        throw new RuntimeErrorException('test,error');
    }
}
