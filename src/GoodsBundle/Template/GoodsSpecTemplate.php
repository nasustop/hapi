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

class GoodsSpecTemplate extends Template
{
    /**
     * 表格数据的来源URL.
     */
    public function getTableApiUri(): string
    {
        return '/goods/spec/list';
    }

    /**
     * 添加按钮的URL，一般为vue页面的path.
     */
    public function getTableHeaderCreateActionUri(): string
    {
        return '/goods/spec/create';
    }

    /**
     * 修改按钮的URL，一般为vue页面的path.
     */
    public function getTableColumnUpdateActionUri(): string
    {
        return '/goods/spec/update';
    }

    /**
     * 表格操作按钮中的删除按钮的URL.
     */
    public function getTableColumnDeleteActionUri(): string
    {
        return '/goods/spec/delete';
    }

    /**
     * 创建表单的保存按钮URL.
     */
    public function getCreateFormSaveApiUri(): string
    {
        return '/goods/spec/create';
    }

    /**
     * 修改表单初始化获取数据的URL.
     */
    public function getUpdateFormInfoApiUri(): string
    {
        return '/goods/spec/info';
    }

    /**
     * 修改表单保存按钮的URL.
     */
    public function getUpdateFormSaveApiUri(): string
    {
        return '/goods/spec/update';
    }

    /**
     * 表格的key.
     */
    public function getTableKey(): string
    {
        return 'spec_id';
    }

    /**
     * 表格的顶部搜索项.
     */
    public function getTableHeaderFilter(): array
    {
        return [
            'spec_name' => [
                'placeholder' => '请输入规格名称',
                'clearable' => true,
            ],
            'show_type' => [
                'placeholder' => '请选择展示类型',
                'clearable' => true,
                'type' => 'select',
                'options' => [
                    [
                        'value' => 'text',
                        'label' => '文本',
                    ],
                    [
                        'value' => 'img',
                        'label' => '图片',
                    ],
                    [
                        'value' => 'all',
                        'label' => '全部',
                    ],
                ],
            ],
        ];
    }

    /**
     * 表格字段集合.
     */
    public function getTableColumns(): array
    {
        return [
            'spec_name' => [
                'title' => '规格名称',
                'align' => 'center',
            ],
            'sort' => [
                'title' => '排序',
                'align' => 'center',
            ],
            'show_type' => [
                'title' => '展示类型',
                'align' => 'center',
                'type' => 'tag',
                'tag' => [
                    'text' => [
                        'type' => 'success',
                        'message' => '文本',
                    ],
                    'img' => [
                        'type' => 'success',
                        'message' => '图片',
                    ],
                    'all' => [
                        'type' => 'success',
                        'message' => '全部',
                    ],
                ],
            ],
        ];
    }

    /**
     * 创建表单字段集合.
     */
    public function getCreateFormColumns(): array
    {
        return [
            'spec_name' => [
                'title' => '规格名称',
                'type' => 'text',
            ],
            'sort' => [
                'title' => '排序',
                'type' => 'number',
            ],
            'show_type' => [
                'title' => '展示类型',
                'type' => 'radio',
                'radio' => [
                    [
                        'value' => 'text',
                        'label' => '文本',
                    ],
                    [
                        'value' => 'img',
                        'label' => '图片',
                    ],
                    [
                        'value' => 'all',
                        'label' => '全部',
                    ],
                ],
            ],
            'spec_value' => [
                'title' => '添加属性',
                'type' => 'dynamic-form-array',
                'dynamic-form-array' => [
                    'form' => [
                        'spec_value_img' => [
                            'title' => '属性图片',
                            'type' => 'image',
                            'placeholder' => '请输入属性图片',
                        ],
                        'spec_value_name' => [
                            'title' => '属性名称',
                            'placeholder' => '请输入属性名称',
                        ],
                        'sort' => [
                            'title' => '排序',
                            'type' => 'number',
                            'placeholder' => '请输入属性排序',
                        ],
                    ],
                    'ruleForm' => [
                        'spec_value_img' => '',
                        'spec_value_name' => '',
                        'sort' => 0,
                    ],
                ],
            ],
        ];
    }

    /**
     * 创建表单提交字段默认值集合.
     */
    public function getCreateFormRuleForm(): array
    {
        return [
            'spec_name' => '',
            'sort' => '',
            'show_type' => '',
            'spec_value' => [],
        ];
    }

    /**
     * 创建表单字段验证规则结合.
     */
    public function getCreateFormRules(): array
    {
        return [
            'spec_name' => [
                [
                    'required' => true,
                    'message' => '规格名称必填',
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
            'show_type' => [
                [
                    'required' => true,
                    'message' => '展示类型必填',
                    'trigger' => 'change',
                ],
            ],
            'spec_value' => [
                [
                    'required' => true,
                    'message' => '商品属性必填',
                    'trigger' => 'change',
                ],
                [
                    'validator' => '(rule, value, callback) => {
                                value.forEach(item => {
                                  if ((this.ruleForm["show_type"] === "all" || this.ruleForm["show_type"] === "img") && item["spec_value_img"] === "") {
                                    callback(new Error("请输入所有属性的图片"))
                                  }
                                  if (item["spec_value_name"] === "") {
                                    callback(new Error("请输入所有属性的名称"))
                                  }
                                  if (item["sort"] === "") {
                                    callback(new Error("请输入所有属性的排序"))
                                  }
                                })
                                callback()
                            }',
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
            'spec_name' => [
                'title' => '规格名称',
                'type' => 'text',
            ],
            'sort' => [
                'title' => '排序',
                'type' => 'number',
            ],
            'show_type' => [
                'title' => '展示类型',
                'type' => 'radio',
                'radio' => [
                    [
                        'value' => 'text',
                        'label' => '文本',
                    ],
                    [
                        'value' => 'img',
                        'label' => '图片',
                    ],
                    [
                        'value' => 'all',
                        'label' => '全部',
                    ],
                ],
            ],
            'spec_value' => [
                'title' => '添加属性',
                'type' => 'dynamic-form-array',
                'dynamic-form-array' => [
                    'form' => [
                        'spec_value_img' => [
                            'title' => '属性图片',
                            'type' => 'image',
                            'placeholder' => '请输入属性图片',
                        ],
                        'spec_value_name' => [
                            'title' => '属性名称',
                            'placeholder' => '请输入属性名称',
                        ],
                        'sort' => [
                            'title' => '排序',
                            'type' => 'number',
                            'placeholder' => '请输入属性排序',
                        ],
                    ],
                    'ruleForm' => [
                        'spec_value_img' => '',
                        'spec_value_name' => '',
                        'sort' => 0,
                    ],
                ],
            ],
        ];
    }

    /**
     * 修改表单提交字段默认值集合.
     */
    public function getUpdateFormRuleForm(): array
    {
        return [
            'spec_name' => '',
            'sort' => '',
            'show_type' => '',
            'spec_value' => [],
        ];
    }

    /**
     * 修改表单字段验证规则结合.
     */
    public function getUpdateFormRules(): array
    {
        return [
            'spec_name' => [
                [
                    'required' => true,
                    'message' => '规格名称必填',
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
            'show_type' => [
                [
                    'required' => true,
                    'message' => '展示类型必填',
                    'trigger' => 'change',
                ],
            ],
            'spec_value' => [
                [
                    'required' => true,
                    'message' => '商品属性必填',
                    'trigger' => 'change',
                ],
                [
                    'validator' => '(rule, value, callback) => {
                                value.forEach(item => {
                                  if ((this.ruleForm["show_type"] === "all" || this.ruleForm["show_type"] === "img") && item["spec_value_img"] === "") {
                                    callback(new Error("请输入所有属性的图片"))
                                  }
                                  if (item["spec_value_name"] === "") {
                                    callback(new Error("请输入所有属性的名称"))
                                  }
                                  if (item["sort"] === "") {
                                    callback(new Error("请输入所有属性的排序"))
                                  }
                                })
                                callback()
                            }',
                    'trigger' => 'change',
                ],
            ],
        ];
    }
}
