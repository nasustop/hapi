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

class GoodsBrandTemplate extends Template
{
    /**
     * 表格数据的来源URL.
     */
    public function getTableApiUri(): string
    {
        return '/goods/brand/list';
    }

    /**
     * 添加按钮的URL，一般为vue页面的path.
     */
    public function getTableHeaderCreateActionUri(): string
    {
        return '/goods/brand/create';
    }

    /**
     * 修改按钮的URL，一般为vue页面的path.
     */
    public function getTableColumnUpdateActionUri(): string
    {
        return '/goods/brand/update';
    }

    /**
     * 表格操作按钮中的删除按钮的URL.
     */
    public function getTableColumnDeleteActionUri(): string
    {
        return '/goods/brand/delete';
    }

    /**
     * 创建表单的保存按钮URL.
     */
    public function getCreateFormSaveApiUri(): string
    {
        return '/goods/brand/create';
    }

    /**
     * 修改表单初始化获取数据的URL.
     */
    public function getUpdateFormInfoApiUri(): string
    {
        return '/goods/brand/info';
    }

    /**
     * 修改表单保存按钮的URL.
     */
    public function getUpdateFormSaveApiUri(): string
    {
        return '/goods/brand/update';
    }

    /**
     * 表格的key.
     */
    public function getTableKey(): string
    {
        return 'brand_id';
    }

    /**
     * 表格的顶部搜索项.
     */
    public function getTableHeaderFilter(): array
    {
        return [
            'brand_name' => [
                'title' => '品牌名称',
                'placeholder' => '请输入品牌名称',
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
            'brand_name' => [
                'title' => '品牌名称',
            ],
            'brand_img' => [
                'title' => '品牌图片',
                'type' => 'image',
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
            'brand_name' => [
                'title' => '品牌名称',
                'type' => 'text',
            ],
            'brand_img' => [
                'title' => '品牌图片',
                'type' => 'image',
            ],
            'sort' => [
                'title' => '排序',
                'type' => 'number',
            ],
        ];
    }

    /**
     * 创建表单提交字段默认值集合.
     */
    public function getCreateFormRuleForm(): array
    {
        return [
            'brand_name' => '',
            'brand_img' => '',
            'sort' => 0,
        ];
    }

    /**
     * 创建表单字段验证规则结合.
     */
    public function getCreateFormRules(): array
    {
        return [
            'brand_name' => [
                [
                    'required' => true,
                    'message' => '品牌名称必填',
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
            'brand_name' => [
                'title' => '品牌名称',
                'type' => 'text',
            ],
            'brand_img' => [
                'title' => '品牌图片',
                'type' => 'image',
            ],
            'sort' => [
                'title' => '排序',
                'type' => 'number',
            ],
        ];
    }

    /**
     * 修改表单提交字段默认值集合.
     */
    public function getUpdateFormRuleForm(): array
    {
        return [
            'brand_name' => '',
            'brand_img' => '',
            'sort' => '',
        ];
    }

    /**
     * 修改表单字段验证规则结合.
     */
    public function getUpdateFormRules(): array
    {
        return [
            'brand_name' => [
                [
                    'required' => true,
                    'message' => '品牌名称必填',
                    'trigger' => 'change',
                ],
            ],
        ];
    }
}
