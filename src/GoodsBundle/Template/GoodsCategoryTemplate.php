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

use GoodsBundle\Repository\GoodsCategoryRepository;
use Nasustop\HapiBase\Template\Template;

class GoodsCategoryTemplate extends Template
{
    protected int $parent_id = 0;

    public function setParentId($parent_id): static
    {
        $this->parent_id = intval($parent_id);
        return $this;
    }

    public function getParentInfo(): array
    {
        if (empty($this->parent_id)) {
            return [];
        }
        $repository = make(GoodsCategoryRepository::class);
        return $repository->getInfo(['category_id' => $this->parent_id]);
    }

    /**
     * 表格数据的来源URL.
     */
    public function getTableApiUri(): string
    {
        return '/goods/category/list';
    }

    /**
     * 添加按钮的URL，一般为vue页面的path.
     */
    public function getTableHeaderCreateActionUri(): string
    {
        return '/goods/category/create';
    }

    /**
     * 修改按钮的URL，一般为vue页面的path.
     */
    public function getTableColumnUpdateActionUri(): string
    {
        return '/goods/category/update';
    }

    /**
     * 表格操作按钮中的删除按钮的URL.
     */
    public function getTableColumnDeleteActionUri(): string
    {
        return '/goods/category/delete';
    }

    /**
     * 创建表单的保存按钮URL.
     */
    public function getCreateFormSaveApiUri(): string
    {
        return '/goods/category/create';
    }

    /**
     * 修改表单初始化获取数据的URL.
     */
    public function getUpdateFormInfoApiUri(): string
    {
        return '/goods/category/info';
    }

    /**
     * 修改表单保存按钮的URL.
     */
    public function getUpdateFormSaveApiUri(): string
    {
        return '/goods/category/update';
    }

    /**
     * 表格的key.
     */
    public function getTableKey(): string
    {
        return 'category_id';
    }

    /**
     * 表格的顶部搜索项.
     */
    public function getTableHeaderFilter(): array
    {
        return [];
    }

    /**
     * 表格字段集合.
     */
    public function getTableColumns(): array
    {
        return [
            'category_name' => [
                'title' => '分类名称',
            ],
            'category_img' => [
                'title' => '分类图片',
                'type' => 'image',
                'align' => 'center',
            ],
            'sort' => [
                'title' => '排序',
                'align' => 'center',
            ],
            'is_show' => [
                'title' => '是否显示',
                'align' => 'center',
                'type' => 'tag',
                'tag' => [
                    'false' => [
                        'value' => false,
                        'type' => 'danger',
                        'message' => '否',
                    ],
                    'true' => [
                        'value' => true,
                        'type' => 'success',
                        'message' => '是',
                    ],
                ],
            ],
        ];
    }

    public function getTableColumnActions(): array
    {
        $result = parent::getTableColumnActions();
        $data = [
            'createChildren' => [
                'title' => '添加子分类',
                'type' => 'success',
                'vShow' => '(row) => { return row["level"] === 0 }',
                'jump' => true,
                'url' => [
                    'const' => $this->getTableHeaderCreateActionUri(),
                    'query' => [
                        'category_id' => 'parent_id',
                    ],
                ],
            ],
        ];
        return array_merge($data, $result);
    }

    /**
     * 创建表单字段集合.
     */
    public function getCreateFormColumns(): array
    {
        $parentInfo = $this->getParentInfo();
        return [
            'parent_id' => [
                'title' => '父节点',
                'type' => 'span',
                'value' => $parentInfo['category_name'] ?? '顶级分类',
            ],
            'category_name' => [
                'title' => '分类名称',
                'type' => 'text',
            ],
            'category_img' => [
                'title' => '分类图片',
                'type' => 'image',
            ],
            'sort' => [
                'title' => '排序',
                'type' => 'number',
            ],
            'is_show' => [
                'title' => '是否显示',
                'type' => 'switch',
            ],
        ];
    }

    /**
     * 创建表单提交字段默认值集合.
     */
    public function getCreateFormRuleForm(): array
    {
        return [
            'parent_id' => $this->parent_id,
            'category_name' => '',
            'category_img' => '',
            'sort' => 0,
            'is_show' => true,
        ];
    }

    /**
     * 创建表单字段验证规则结合.
     */
    public function getCreateFormRules(): array
    {
        return [
            'parent_id' => [
                [
                    'required' => true,
                    'message' => '父节点ID必填',
                    'trigger' => 'change',
                ],
            ],
            'category_name' => [
                [
                    'required' => true,
                    'message' => '分类名称必填',
                    'trigger' => 'change',
                ],
            ],
            'is_show' => [
                [
                    'required' => true,
                    'message' => '是否显示必填',
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
        $parentInfo = $this->getParentInfo();
        return [
            'parent_id' => [
                'title' => '父节点',
                'type' => 'span',
                'value' => $parentInfo['category_name'] ?? '顶级分类',
            ],
            'category_name' => [
                'title' => '分类名称',
                'type' => 'text',
            ],
            'category_img' => [
                'title' => '分类图片',
                'type' => 'image',
            ],
            'sort' => [
                'title' => '排序',
                'type' => 'number',
            ],
            'is_show' => [
                'title' => '是否显示',
                'type' => 'switch',
            ],
        ];
    }

    /**
     * 修改表单提交字段默认值集合.
     */
    public function getUpdateFormRuleForm(): array
    {
        return [
            'parent_id' => $this->parent_id,
            'category_name' => '',
            'category_img' => '',
            'sort' => '',
            'is_show' => true,
        ];
    }

    /**
     * 修改表单字段验证规则结合.
     */
    public function getUpdateFormRules(): array
    {
        return [
            'parent_id' => [
                [
                    'required' => true,
                    'message' => '父节点ID必填',
                    'trigger' => 'change',
                ],
            ],
            'category_name' => [
                [
                    'required' => true,
                    'message' => '分类名称必填',
                    'trigger' => 'change',
                ],
            ],
            'is_show' => [
                [
                    'required' => true,
                    'message' => '是否显示必填',
                    'trigger' => 'change',
                ],
            ],
        ];
    }
}
