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

class GoodsSpuTemplate extends Template
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
        return 'spu_id';
    }

    /**
     * 表格的顶部搜索项.
     */
    public function getTableHeaderFilter(): array
    {
        return [
            'spu_name' => [
                'placeholder' => '请输入商品名称',
                'clearable' => true,
            ],
            'spu_brief' => [
                'placeholder' => '请输入商品描述',
                'clearable' => true,
            ],
            'spu_thumb' => [
                'placeholder' => '请输入商品缩略图',
                'clearable' => true,
            ],
            'spu_images' => [
                'placeholder' => '请输入商品轮播图',
                'clearable' => true,
            ],
            'spu_intro' => [
                'placeholder' => '请输入商品详情',
                'clearable' => true,
            ],
            'status' => [
                'placeholder' => '请输入商品状态',
                'clearable' => true,
            ],
            'spu_type' => [
                'placeholder' => '请输入商品类型',
                'clearable' => true,
            ],
            'price_min' => [
                'placeholder' => '请输入最低价',
                'clearable' => true,
            ],
            'price_max' => [
                'placeholder' => '请输入最高价',
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
            'spu_name' => [
                'title' => '商品名称',
            ],
            'spu_brief' => [
                'title' => '商品描述',
            ],
            'spu_thumb' => [
                'title' => '商品缩略图',
            ],
            'spu_images' => [
                'title' => '商品轮播图',
            ],
            'spu_intro' => [
                'title' => '商品详情',
            ],
            'status' => [
                'title' => '商品状态',
            ],
            'spu_type' => [
                'title' => '商品类型',
            ],
            'price_min' => [
                'title' => '最低价',
            ],
            'price_max' => [
                'title' => '最高价',
            ],
        ];
    }

    /**
     * 创建表单字段集合.
     */
    public function getCreateFormColumns(): array
    {
        return [
            'spu_name' => [
                'title' => '商品名称',
                'type' => 'text',
            ],
            'spu_brief' => [
                'title' => '商品描述',
                'type' => 'text',
            ],
            'spu_thumb' => [
                'title' => '商品缩略图',
                'type' => 'text',
            ],
            'spu_images' => [
                'title' => '商品轮播图',
                'type' => 'text',
            ],
            'spu_intro' => [
                'title' => '商品详情',
                'type' => 'text',
            ],
            'status' => [
                'title' => '商品状态',
                'type' => 'text',
            ],
            'spu_type' => [
                'title' => '商品类型',
                'type' => 'text',
            ],
            'price_min' => [
                'title' => '最低价',
                'type' => 'text',
            ],
            'price_max' => [
                'title' => '最高价',
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
            'spu_name' => '',
            'spu_brief' => '',
            'spu_thumb' => '',
            'spu_images' => '',
            'spu_intro' => '',
            'status' => '',
            'spu_type' => '',
            'price_min' => '',
            'price_max' => '',
        ];
    }

    /**
     * 创建表单字段验证规则结合.
     */
    public function getCreateFormRules(): array
    {
        return [
            'spu_name' => [
                [
                    'required' => true,
                    'message' => '商品名称必填',
                    'trigger' => 'change',
                ],
            ],
            'spu_brief' => [
                [
                    'required' => true,
                    'message' => '商品描述必填',
                    'trigger' => 'change',
                ],
            ],
            'spu_thumb' => [
                [
                    'required' => true,
                    'message' => '商品缩略图必填',
                    'trigger' => 'change',
                ],
            ],
            'spu_images' => [
                [
                    'required' => true,
                    'message' => '商品轮播图必填',
                    'trigger' => 'change',
                ],
            ],
            'spu_intro' => [
                [
                    'required' => true,
                    'message' => '商品详情必填',
                    'trigger' => 'change',
                ],
            ],
            'status' => [
                [
                    'required' => true,
                    'message' => '商品状态必填',
                    'trigger' => 'change',
                ],
            ],
            'spu_type' => [
                [
                    'required' => true,
                    'message' => '商品类型必填',
                    'trigger' => 'change',
                ],
            ],
            'price_min' => [
                [
                    'required' => true,
                    'message' => '最低价必填',
                    'trigger' => 'change',
                ],
            ],
            'price_max' => [
                [
                    'required' => true,
                    'message' => '最高价必填',
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
            'spu_name' => [
                'title' => '商品名称',
                'type' => 'text',
            ],
            'spu_brief' => [
                'title' => '商品描述',
                'type' => 'text',
            ],
            'spu_thumb' => [
                'title' => '商品缩略图',
                'type' => 'text',
            ],
            'spu_images' => [
                'title' => '商品轮播图',
                'type' => 'text',
            ],
            'spu_intro' => [
                'title' => '商品详情',
                'type' => 'text',
            ],
            'status' => [
                'title' => '商品状态',
                'type' => 'text',
            ],
            'spu_type' => [
                'title' => '商品类型',
                'type' => 'text',
            ],
            'price_min' => [
                'title' => '最低价',
                'type' => 'text',
            ],
            'price_max' => [
                'title' => '最高价',
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
            'spu_name' => '',
            'spu_brief' => '',
            'spu_thumb' => '',
            'spu_images' => '',
            'spu_intro' => '',
            'status' => '',
            'spu_type' => '',
            'price_min' => '',
            'price_max' => '',
        ];
    }

    /**
     * 修改表单字段验证规则结合.
     */
    public function getUpdateFormRules(): array
    {
        return [
            'spu_name' => [
                [
                    'required' => true,
                    'message' => '商品名称必填',
                    'trigger' => 'change',
                ],
            ],
            'spu_brief' => [
                [
                    'required' => true,
                    'message' => '商品描述必填',
                    'trigger' => 'change',
                ],
            ],
            'spu_thumb' => [
                [
                    'required' => true,
                    'message' => '商品缩略图必填',
                    'trigger' => 'change',
                ],
            ],
            'spu_images' => [
                [
                    'required' => true,
                    'message' => '商品轮播图必填',
                    'trigger' => 'change',
                ],
            ],
            'spu_intro' => [
                [
                    'required' => true,
                    'message' => '商品详情必填',
                    'trigger' => 'change',
                ],
            ],
            'status' => [
                [
                    'required' => true,
                    'message' => '商品状态必填',
                    'trigger' => 'change',
                ],
            ],
            'spu_type' => [
                [
                    'required' => true,
                    'message' => '商品类型必填',
                    'trigger' => 'change',
                ],
            ],
            'price_min' => [
                [
                    'required' => true,
                    'message' => '最低价必填',
                    'trigger' => 'change',
                ],
            ],
            'price_max' => [
                [
                    'required' => true,
                    'message' => '最高价必填',
                    'trigger' => 'change',
                ],
            ],
        ];
    }
}
