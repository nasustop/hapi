<?php

declare(strict_types=1);

namespace GoodsBundle\Template;

use Nasustop\HapiBase\Template\Template;

class GoodsParamTemplate extends Template
{
    /**
     * 表格数据的来源URL.
     */
    public function getTableApiUri(): string
    {
        return '/goods/params/list';
    }

    /**
     * 添加按钮的URL，一般为vue页面的path.
     */
    public function getTableHeaderCreateActionUri(): string
    {
        return '/goods/params/create';
    }

    /**
     * 修改按钮的URL，一般为vue页面的path.
     */
    public function getTableColumnUpdateActionUri(): string
    {
        return '/goods/params/update';
    }

    /**
     * 表格操作按钮中的删除按钮的URL.
     */
    public function getTableColumnDeleteActionUri(): string
    {
        return '/goods/params/delete';
    }

    /**
     * 创建表单的保存按钮URL.
     */
    public function getCreateFormSaveApiUri(): string
    {
        return '/goods/params/create';
    }

    /**
     * 修改表单初始化获取数据的URL.
     */
    public function getUpdateFormInfoApiUri(): string
    {
        return '/goods/params/info';
    }

    /**
     * 修改表单保存按钮的URL.
     */
    public function getUpdateFormSaveApiUri(): string
    {
        return '/goods/params/update';
    }

    /**
     * 表格的key.
     */
    public function getTableKey(): string
    {
        return 'params_id';
    }

    /**
     * 表格的顶部搜索项.
     */
    public function getTableHeaderFilter(): array
    {
        return [
			'params_name' => [
				'placeholder' => '请输入参数名称',
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
			'params_name' => [
				'title' => '参数名称',
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
			'params_name' => [
				'title' => '参数名称',
				'type' => 'text',
			],
			'sort' => [
				'title' => '排序',
				'type' => 'number',
			],
            'params_value' => [
                'title' => '添加参数值',
                'type' => 'dynamic-form-array',
                'dynamic-form-array' => [
                    'form' => [
                        'params_value_name' => [
                            'title' => '参数值',
                            'placeholder' => '请输入参数值',
                        ],
                    ],
                    'ruleForm' => [
                        'params_value_name' => '',
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
			'params_name' => '',
			'sort' => '',
            'params_value' => [],
		];
    }

    /**
     * 创建表单字段验证规则结合.
     */
    public function getCreateFormRules(): array
    {
        return [
			'params_name' => [
				[
					'required' => true,
					'message' => '参数名称必填',
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
            'params_value' => [
                [
                    'required' => true,
                    'message' => '参数值必填',
                    'trigger' => 'change',
                ],
                [
                    'validator' => '(rule, value, callback) => {
                                value.forEach(item => {
                                  if (item["params_value_name"] === "") {
                                    callback(new Error("请输入参数值"))
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
			'params_name' => [
				'title' => '参数名称',
				'type' => 'text',
			],
			'sort' => [
				'title' => '排序',
				'type' => 'number',
			],
            'params_value' => [
                'title' => '添加参数值',
                'type' => 'dynamic-form-array',
                'dynamic-form-array' => [
                    'form' => [
                        'params_value_name' => [
                            'title' => '参数值',
                            'placeholder' => '请输入参数值',
                        ],
                    ],
                    'ruleForm' => [
                        'params_value_name' => '',
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
			'params_name' => '',
			'sort' => '',
            'params_value' => [],
		];
    }

    /**
     * 修改表单字段验证规则结合.
     */
    public function getUpdateFormRules(): array
    {
        return [
			'params_name' => [
				[
					'required' => true,
					'message' => '参数名称必填',
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
            'params_value' => [
                [
                    'required' => true,
                    'message' => '参数值必填',
                    'trigger' => 'change',
                ],
                [
                    'validator' => '(rule, value, callback) => {
                                value.forEach(item => {
                                  if (item["params_value_name"] === "") {
                                    callback(new Error("请输入参数值"))
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
