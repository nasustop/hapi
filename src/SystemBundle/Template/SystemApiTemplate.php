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

namespace SystemBundle\Template;

use Nasustop\HapiBase\Template\Template;

class SystemApiTemplate extends Template
{
    /**
     * 表格数据的来源URL.
     */
    public function getTableApiUri(): string
    {
        return '';
    }

    /**
     * 添加按钮的URL，一般为vue页面的path.
     */
    public function getTableHeaderCreateActionUri(): string
    {
        return '';
    }

    /**
     * 修改按钮的URL，一般为vue页面的path.
     */
    public function getTableColumnUpdateActionUri(): string
    {
        return '';
    }

    /**
     * 表格操作按钮中的删除按钮的URL.
     */
    public function getTableColumnDeleteActionUri(): string
    {
        return '';
    }

    /**
     * 创建表单的保存按钮URL.
     */
    public function getCreateFormSaveApiUri(): string
    {
        return '';
    }

    /**
     * 修改表单初始化获取数据的URL.
     */
    public function getUpdateFormInfoApiUri(): string
    {
        return '';
    }

    /**
     * 修改表单保存按钮的URL.
     */
    public function getUpdateFormSaveApiUri(): string
    {
        return '';
    }

    /**
     * 表格的key.
     */
    public function getTableKey(): string
    {
        return 'api_id';
    }

    /**
     * 表格的顶部搜索项.
     */
    public function getTableHeaderFilter(): array
    {
        return [
            'api_name' => [
                'placeholder' => '请输入接口名称',
                'clearable' => true,
            ],
            'api_alias' => [
                'placeholder' => '请输入接口别名，全局唯一',
                'clearable' => true,
            ],
            'api_method' => [
                'placeholder' => '请输入请求方式',
                'clearable' => true,
            ],
            'api_uri' => [
                'placeholder' => '请输入接口地址',
                'clearable' => true,
            ],
            'api_action' => [
                'placeholder' => '请输入接口方法',
                'clearable' => true,
            ],
        ];
    }

    /**
     * 表格字段集合.
     */
    public function getTableColumns(): array
    {
        return [
            'api_name' => [
                'title' => '接口名称',
            ],
            'api_alias' => [
                'title' => '接口别名，全局唯一',
            ],
            'api_method' => [
                'title' => '请求方式',
            ],
            'api_uri' => [
                'title' => '接口地址',
            ],
            'api_action' => [
                'title' => '接口方法',
            ],
        ];
    }

    /**
     * 创建表单字段集合.
     */
    public function getCreateFormColumns(): array
    {
        return [
            'api_name' => [
                'title' => '接口名称',
                'type' => 'text',
            ],
            'api_alias' => [
                'title' => '接口别名，全局唯一',
                'type' => 'text',
            ],
            'api_method' => [
                'title' => '请求方式',
                'type' => 'text',
            ],
            'api_uri' => [
                'title' => '接口地址',
                'type' => 'text',
            ],
            'api_action' => [
                'title' => '接口方法',
                'type' => 'text',
            ],
        ];
    }

    /**
     * 创建表单提交字段默认值集合.
     */
    public function getCreateFormRuleForm(): array
    {
        return [
            'api_name' => '',
            'api_alias' => '',
            'api_method' => '',
            'api_uri' => '',
            'api_action' => '',
        ];
    }

    /**
     * 创建表单字段验证规则结合.
     */
    public function getCreateFormRules(): array
    {
        return [
            'api_name' => [
                [
                    'required' => true,
                    'message' => '接口名称必填',
                    'trigger' => 'change',
                ],
            ],
            'api_alias' => [
                [
                    'required' => true,
                    'message' => '接口别名，全局唯一必填',
                    'trigger' => 'change',
                ],
            ],
            'api_method' => [
                [
                    'required' => true,
                    'message' => '请求方式必填',
                    'trigger' => 'change',
                ],
            ],
            'api_uri' => [
                [
                    'required' => true,
                    'message' => '接口地址必填',
                    'trigger' => 'change',
                ],
            ],
            'api_action' => [
                [
                    'required' => true,
                    'message' => '接口方法必填',
                    'trigger' => 'change',
                ],
            ],
        ];
    }

    /**
     * 修改表单字段集合.
     */
    public function getUpdateFormColumns(): array
    {
        return [
            'api_name' => [
                'title' => '接口名称',
                'type' => 'text',
            ],
            'api_alias' => [
                'title' => '接口别名，全局唯一',
                'type' => 'text',
            ],
            'api_method' => [
                'title' => '请求方式',
                'type' => 'text',
            ],
            'api_uri' => [
                'title' => '接口地址',
                'type' => 'text',
            ],
            'api_action' => [
                'title' => '接口方法',
                'type' => 'text',
            ],
        ];
    }

    /**
     * 修改表单提交字段默认值集合.
     */
    public function getUpdateFormRuleForm(): array
    {
        return [
            'api_name' => '',
            'api_alias' => '',
            'api_method' => '',
            'api_uri' => '',
            'api_action' => '',
        ];
    }

    /**
     * 修改表单字段验证规则结合.
     */
    public function getUpdateFormRules(): array
    {
        return [
            'api_name' => [
                [
                    'required' => true,
                    'message' => '接口名称必填',
                    'trigger' => 'change',
                ],
            ],
            'api_alias' => [
                [
                    'required' => true,
                    'message' => '接口别名，全局唯一必填',
                    'trigger' => 'change',
                ],
            ],
            'api_method' => [
                [
                    'required' => true,
                    'message' => '请求方式必填',
                    'trigger' => 'change',
                ],
            ],
            'api_uri' => [
                [
                    'required' => true,
                    'message' => '接口地址必填',
                    'trigger' => 'change',
                ],
            ],
            'api_action' => [
                [
                    'required' => true,
                    'message' => '接口方法必填',
                    'trigger' => 'change',
                ],
            ],
        ];
    }
}
