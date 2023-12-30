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

class GoodsSkuTemplate extends Template
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
        return 'sku_id';
    }

    /**
     * 表格的顶部搜索项.
     */
    public function getTableHeaderFilter(): array
    {
        return [
            'spu_id' => [
                'placeholder' => '请输入商品SPU_ID',
                'clearable' => true,
            ],
            'sku_thumb' => [
                'placeholder' => '请输入商品缩略图',
                'clearable' => true,
            ],
            'sale_price' => [
                'placeholder' => '请输入售卖价格',
                'clearable' => true,
            ],
            'market_price' => [
                'placeholder' => '请输入市场价格',
                'clearable' => true,
            ],
            'sku_code' => [
                'placeholder' => '请输入商品编码',
                'clearable' => true,
            ],
            'stock_num' => [
                'placeholder' => '请输入库存',
                'clearable' => true,
            ],
            'sold_num' => [
                'placeholder' => '请输入已售数量',
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
            'spu_id' => [
                'title' => '商品SPU_ID',
            ],
            'sku_thumb' => [
                'title' => '商品缩略图',
            ],
            'sale_price' => [
                'title' => '售卖价格',
            ],
            'market_price' => [
                'title' => '市场价格',
            ],
            'sku_code' => [
                'title' => '商品编码',
            ],
            'stock_num' => [
                'title' => '库存',
            ],
            'sold_num' => [
                'title' => '已售数量',
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
            'spu_id' => [
                'title' => '商品SPU_ID',
                'type' => 'text',
            ],
            'sku_thumb' => [
                'title' => '商品缩略图',
                'type' => 'text',
            ],
            'sale_price' => [
                'title' => '售卖价格',
                'type' => 'text',
            ],
            'market_price' => [
                'title' => '市场价格',
                'type' => 'text',
            ],
            'sku_code' => [
                'title' => '商品编码',
                'type' => 'text',
            ],
            'stock_num' => [
                'title' => '库存',
                'type' => 'text',
            ],
            'sold_num' => [
                'title' => '已售数量',
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
            'spu_id' => '',
            'sku_thumb' => '',
            'sale_price' => '',
            'market_price' => '',
            'sku_code' => '',
            'stock_num' => '',
            'sold_num' => '',
            'sort' => '',
        ];
    }

    /**
     * 创建表单字段验证规则结合.
     */
    public function getCreateFormRules(): array
    {
        return [
            'spu_id' => [
                [
                    'required' => true,
                    'message' => '商品SPU_ID必填',
                    'trigger' => 'change',
                ],
            ],
            'sku_thumb' => [
                [
                    'required' => true,
                    'message' => '商品缩略图必填',
                    'trigger' => 'change',
                ],
            ],
            'sale_price' => [
                [
                    'required' => true,
                    'message' => '售卖价格必填',
                    'trigger' => 'change',
                ],
            ],
            'market_price' => [
                [
                    'required' => true,
                    'message' => '市场价格必填',
                    'trigger' => 'change',
                ],
            ],
            'sku_code' => [
                [
                    'required' => true,
                    'message' => '商品编码必填',
                    'trigger' => 'change',
                ],
            ],
            'stock_num' => [
                [
                    'required' => true,
                    'message' => '库存必填',
                    'trigger' => 'change',
                ],
            ],
            'sold_num' => [
                [
                    'required' => true,
                    'message' => '已售数量必填',
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
            'spu_id' => [
                'title' => '商品SPU_ID',
                'type' => 'text',
            ],
            'sku_thumb' => [
                'title' => '商品缩略图',
                'type' => 'text',
            ],
            'sale_price' => [
                'title' => '售卖价格',
                'type' => 'text',
            ],
            'market_price' => [
                'title' => '市场价格',
                'type' => 'text',
            ],
            'sku_code' => [
                'title' => '商品编码',
                'type' => 'text',
            ],
            'stock_num' => [
                'title' => '库存',
                'type' => 'text',
            ],
            'sold_num' => [
                'title' => '已售数量',
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
            'spu_id' => '',
            'sku_thumb' => '',
            'sale_price' => '',
            'market_price' => '',
            'sku_code' => '',
            'stock_num' => '',
            'sold_num' => '',
            'sort' => '',
        ];
    }

    /**
     * 修改表单字段验证规则结合.
     */
    public function getUpdateFormRules(): array
    {
        return [
            'spu_id' => [
                [
                    'required' => true,
                    'message' => '商品SPU_ID必填',
                    'trigger' => 'change',
                ],
            ],
            'sku_thumb' => [
                [
                    'required' => true,
                    'message' => '商品缩略图必填',
                    'trigger' => 'change',
                ],
            ],
            'sale_price' => [
                [
                    'required' => true,
                    'message' => '售卖价格必填',
                    'trigger' => 'change',
                ],
            ],
            'market_price' => [
                [
                    'required' => true,
                    'message' => '市场价格必填',
                    'trigger' => 'change',
                ],
            ],
            'sku_code' => [
                [
                    'required' => true,
                    'message' => '商品编码必填',
                    'trigger' => 'change',
                ],
            ],
            'stock_num' => [
                [
                    'required' => true,
                    'message' => '库存必填',
                    'trigger' => 'change',
                ],
            ],
            'sold_num' => [
                [
                    'required' => true,
                    'message' => '已售数量必填',
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
