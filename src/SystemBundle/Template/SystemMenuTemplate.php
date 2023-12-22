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
use SystemBundle\Repository\SystemMenuRepository;

class SystemMenuTemplate extends Template
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
        $repository = make(SystemMenuRepository::class);
        return $repository->getInfo(['menu_id' => $this->parent_id]);
    }

    /**
     * 表格数据的来源URL.
     */
    public function getTableApiUri(): string
    {
        return '/system/auth/menu/list';
    }

    /**
     * 添加按钮的URL，一般为vue页面的path.
     */
    public function getTableHeaderCreateActionUri(): string
    {
        return '/system/menu/create';
    }

    /**
     * 修改按钮的URL，一般为vue页面的path.
     */
    public function getTableColumnUpdateActionUri(): string
    {
        return '/system/menu/update';
    }

    /**
     * 表格操作按钮中的删除按钮的URL.
     */
    public function getTableColumnDeleteActionUri(): string
    {
        return '/system/auth/menu/delete';
    }

    /**
     * 创建表单的保存按钮URL.
     */
    public function getCreateFormSaveApiUri(): string
    {
        return '/system/auth/menu/create';
    }

    /**
     * 修改表单初始化获取数据的URL.
     */
    public function getUpdateFormInfoApiUri(): string
    {
        return '/system/auth/menu/info';
    }

    /**
     * 修改表单保存按钮的URL.
     */
    public function getUpdateFormSaveApiUri(): string
    {
        return '/system/auth/menu/update';
    }

    /**
     * 表格的key.
     */
    public function getTableKey(): string
    {
        return 'menu_id';
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
            'menu_name' => [
                'title' => '菜单名称',
            ],
            'menu_alias' => [
                'title' => '菜单别名',
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
                    '0' => [
                        'type' => 'error',
                        'message' => '否',
                    ],
                    '1' => [
                        'type' => 'success',
                        'message' => '是',
                    ],
                ],
            ],
        ];
    }

    /**
     * 修改表格某一行的跳转地址的参数.
     */
    public function getTableColumnUpdateActionQuery(): array
    {
        return [
            $this->getTableKey() => $this->getTableKey(),
        ];
    }

    public function getTableColumnActions(): array
    {
        $result = parent::getTableColumnActions();
        $data = [
            'createChildren' => [
                'title' => '添加子菜单',
                'type' => 'success',
                'jump' => true,
                'url' => [
                    'const' => $this->getTableHeaderCreateActionUri(),
                    'query' => [
                        'menu_id' => 'parent_id',
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
                'value' => $parentInfo['menu_name'] ?? '顶级菜单',
            ],
            'menu_name' => [
                'title' => '菜单名称',
                'type' => 'text',
            ],
            'menu_alias' => [
                'title' => '菜单别名',
                'type' => 'text',
            ],
            'sort' => [
                'title' => '排序',
                'type' => 'number',
            ],
            'is_show' => [
                'title' => '是否显示',
                'type' => 'switch',
            ],
            'api_ids' => [
                'type' => 'dialog',
                'title' => 'API权限',
                'btn' => [
                    'title' => '选择',
                    'type' => 'primary',
                    'size' => 'mini',
                ],
                'dialog' => [
                    'title' => '选择API权限',
                    'type' => 'table',
                    'header' => [
                        'filter' => [
                            'api_name' => [
                                'placeholder' => '请输入API名称',
                                'clearable' => true,
                            ],
                            'api_alias' => [
                                'placeholder' => '请输入API别名',
                                'clearable' => true,
                            ],
                        ],
                        'actions' => [
                            'search' => [
                                'title' => '搜索',
                                'type' => 'primary',
                                'icon' => 'el-icon-search',
                                'is_search' => true,
                                'url' => [
                                    'const' => '/system/auth/api/list',
                                    'query' => [
                                        'page' => 'page',
                                        'page_size' => 'page_size',
                                        'api_name' => 'api_name',
                                        'api_alias' => 'api_alias',
                                    ],
                                    'refresh' => false,
                                ],
                            ],
                        ],
                    ],
                    'table' => [
                        'url' => [
                            'const' => '/system/auth/api/list',
                        ],
                        'page' => 1,
                        'page_size' => 10,
                        'key' => 'api_id',
                        'columns' => [
                            'api_name' => [
                                'title' => 'API名称',
                            ],
                            'api_alias' => [
                                'title' => 'API别名',
                            ],
                            'api_uri' => [
                                'title' => 'API路径',
                            ],
                            'api_action' => [
                                'title' => 'API方法',
                            ],
                        ],
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
            'parent_id' => $this->parent_id,
            'menu_name' => '',
            'menu_alias' => '',
            'sort' => '',
            'is_show' => true,
            'api_ids' => [],
        ];
    }

    /**
     * 创建表单字段验证规则结合.
     */
    public function getCreateFormRules(): array
    {
        return [
            'menu_name' => [
                [
                    'required' => true,
                    'message' => '菜单名称必填',
                    'trigger' => 'change',
                ],
            ],
            'menu_alias' => [
                [
                    'required' => true,
                    'message' => '菜单别名必填',
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
                'title' => '父节点ID',
                'type' => 'span',
                'value' => $parentInfo['menu_name'] ?? '顶级菜单',
            ],
            'menu_name' => [
                'title' => '菜单名称',
                'type' => 'text',
            ],
            'menu_alias' => [
                'title' => '菜单别名',
                'type' => 'text',
            ],
            'sort' => [
                'title' => '排序',
                'type' => 'text',
            ],
            'is_show' => [
                'title' => '是否显示',
                'type' => 'switch',
            ],
            'api_ids' => [
                'type' => 'dialog',
                'title' => 'API权限',
                'btn' => [
                    'title' => '选择',
                    'type' => 'primary',
                    'size' => 'mini',
                ],
                'dialog' => [
                    'title' => '选择API权限',
                    'type' => 'table',
                    'header' => [
                        'filter' => [
                            'api_name' => [
                                'placeholder' => '请输入API名称',
                                'clearable' => true,
                            ],
                            'api_alias' => [
                                'placeholder' => '请输入API别名',
                                'clearable' => true,
                            ],
                        ],
                        'actions' => [
                            'search' => [
                                'title' => '搜索',
                                'type' => 'primary',
                                'icon' => 'el-icon-search',
                                'is_search' => true,
                                'url' => [
                                    'const' => '/system/auth/api/list',
                                    'query' => [
                                        'page' => 'page',
                                        'page_size' => 'page_size',
                                        'api_name' => 'api_name',
                                        'api_alias' => 'api_alias',
                                    ],
                                    'refresh' => false,
                                ],
                            ],
                        ],
                    ],
                    'table' => [
                        'url' => [
                            'const' => '/system/auth/api/list',
                        ],
                        'page' => 1,
                        'page_size' => 10,
                        'key' => 'api_id',
                        'columns' => [
                            'api_name' => [
                                'title' => 'API名称',
                            ],
                            'api_alias' => [
                                'title' => 'API别名',
                            ],
                            'api_uri' => [
                                'title' => 'API路径',
                            ],
                            'api_action' => [
                                'title' => 'API方法',
                            ],
                        ],
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
            'parent_id' => $this->parent_id,
            'menu_name' => '',
            'menu_alias' => '',
            'sort' => '',
            'is_show' => true,
            'api_ids' => [],
        ];
    }

    /**
     * 修改表单字段验证规则结合.
     */
    public function getUpdateFormRules(): array
    {
        return [
            'menu_name' => [
                [
                    'required' => true,
                    'message' => '菜单名称必填',
                    'trigger' => 'change',
                ],
            ],
            'menu_alias' => [
                [
                    'required' => true,
                    'message' => '菜单别名必填',
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
