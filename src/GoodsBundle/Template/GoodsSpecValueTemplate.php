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

namespace GoodsBundle\Template;

use Nasustop\HapiBase\Template\Template;

class GoodsSpecValueTemplate extends Template
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
        return 'spec_value_id';
    }

    /**
     * 表格的顶部搜索项.
     */
    public function getTableHeaderFilter(): array
    {
        return [
            'spec_id' => [
                'placeholder' => '请输入规格ID',
                'clearable' => true,
            ],
            'spec_value_name' => [
                'placeholder' => '请输入属性名称',
                'clearable' => true,
            ],
            'spec_value_img' => [
                'placeholder' => '请输入属性图片',
                'clearable' => true,
            ],
            'sort' => [
                'placeholder' => '请输入排序',
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
            'spec_id' => [
                'title' => '规格ID',
            ],
            'spec_value_name' => [
                'title' => '属性名称',
            ],
            'spec_value_img' => [
                'title' => '属性图片',
            ],
            'sort' => [
                'title' => '排序',
            ],
        ];
    }

    /**
     * 创建表单字段集合.
     */
    public function getCreateFormColumns(): array
    {
        return [
            'spec_id' => [
                'title' => '规格ID',
                'type' => 'text',
            ],
            'spec_value_name' => [
                'title' => '属性名称',
                'type' => 'text',
            ],
            'spec_value_img' => [
                'title' => '属性图片',
                'type' => 'text',
            ],
            'sort' => [
                'title' => '排序',
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
            'spec_id' => '',
            'spec_value_name' => '',
            'spec_value_img' => '',
            'sort' => '',
        ];
    }

    /**
     * 创建表单字段验证规则结合.
     */
    public function getCreateFormRules(): array
    {
        return [
            'spec_id' => [
                [
                    'required' => true,
                    'message' => '规格ID必填',
                    'trigger' => 'change',
                ],
            ],
            'spec_value_name' => [
                [
                    'required' => true,
                    'message' => '属性名称必填',
                    'trigger' => 'change',
                ],
            ],
            'sort' => [
                [
                    'required' => true,
                    'message' => '排序必填',
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
            'spec_id' => [
                'title' => '规格ID',
                'type' => 'text',
            ],
            'spec_value_name' => [
                'title' => '属性名称',
                'type' => 'text',
            ],
            'spec_value_img' => [
                'title' => '属性图片',
                'type' => 'text',
            ],
            'sort' => [
                'title' => '排序',
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
            'spec_id' => '',
            'spec_value_name' => '',
            'spec_value_img' => '',
            'sort' => '',
        ];
    }

    /**
     * 修改表单字段验证规则结合.
     */
    public function getUpdateFormRules(): array
    {
        return [
            'spec_id' => [
                [
                    'required' => true,
                    'message' => '规格ID必填',
                    'trigger' => 'change',
                ],
            ],
            'spec_value_name' => [
                [
                    'required' => true,
                    'message' => '属性名称必填',
                    'trigger' => 'change',
                ],
            ],
            'sort' => [
                [
                    'required' => true,
                    'message' => '排序必填',
                    'trigger' => 'change',
                ],
            ],
        ];
    }
}
