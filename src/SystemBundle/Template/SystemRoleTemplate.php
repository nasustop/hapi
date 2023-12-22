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

class SystemRoleTemplate extends Template
{
    protected SystemMenuRepository $systemMenuRepository;

    /**
     * 表格数据的来源URL.
     */
    public function getTableApiUri(): string
    {
        return '/system/auth/role/list';
    }

    /**
     * 添加按钮的URL，一般为vue页面的path.
     */
    public function getTableHeaderCreateActionUri(): string
    {
        return '/system/role/create';
    }

    /**
     * 修改按钮的URL，一般为vue页面的path.
     */
    public function getTableColumnUpdateActionUri(): string
    {
        return '/system/role/update';
    }

    /**
     * 表格操作按钮中的删除按钮的URL.
     */
    public function getTableColumnDeleteActionUri(): string
    {
        return '/system/auth/role/delete';
    }

    /**
     * 创建表单的保存按钮URL.
     */
    public function getCreateFormSaveApiUri(): string
    {
        return '/system/auth/role/create';
    }

    /**
     * 修改表单初始化获取数据的URL.
     */
    public function getUpdateFormInfoApiUri(): string
    {
        return '/system/auth/role/info';
    }

    /**
     * 修改表单保存按钮的URL.
     */
    public function getUpdateFormSaveApiUri(): string
    {
        return '/system/auth/role/update';
    }

    /**
     * 表格的key.
     */
    public function getTableKey(): string
    {
        return 'role_id';
    }

    /**
     * 表格的顶部搜索项.
     */
    public function getTableHeaderFilter(): array
    {
        return [
            'role_name' => [
                'placeholder' => '请输入角色名称',
                'clearable' => true,
            ],
            'role_alias' => [
                'placeholder' => '请输入角色别名',
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
            'role_name' => [
                'title' => '角色名称',
            ],
            'role_alias' => [
                'title' => '角色别名',
            ],
        ];
    }

    /**
     * 创建表单字段集合.
     */
    public function getCreateFormColumns(): array
    {
        $menuData = $this->getSystemMenuRepository()->findTreeByMenuIds();
        $menuTree = $menuData['tree'] ?? [];
        return [
            'role_name' => [
                'title' => '角色名称',
                'type' => 'text',
            ],
            'role_alias' => [
                'title' => '角色别名',
                'type' => 'text',
            ],
            'menu_ids' => [
                'type' => 'tree',
                'title' => '菜单权限',
                'data' => $menuTree,
                'dataKey' => 'menu_id',
                'props' => [
                    'children' => 'children',
                    'label' => 'menu_name',
                ],
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
            'role_name' => '',
            'role_alias' => '',
            'menu_ids' => [],
            'api_ids' => [],
        ];
    }

    /**
     * 创建表单字段验证规则结合.
     */
    public function getCreateFormRules(): array
    {
        return [
            'role_name' => [
                [
                    'required' => true,
                    'message' => '角色名称必填',
                    'trigger' => 'change',
                ],
            ],
            'role_alias' => [
                [
                    'required' => true,
                    'message' => '角色别名必填',
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
        $menuData = $this->getSystemMenuRepository()->findTreeByMenuIds();
        $menuTree = $menuData['tree'] ?? [];
        return [
            'role_name' => [
                'title' => '角色名称',
                'type' => 'text',
            ],
            'role_alias' => [
                'title' => '角色别名',
                'type' => 'text',
            ],
            'menu_ids' => [
                'type' => 'tree',
                'title' => '菜单权限',
                'data' => $menuTree,
                'dataKey' => 'menu_id',
                'props' => [
                    'children' => 'children',
                    'label' => 'menu_name',
                ],
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
            'role_name' => '',
            'role_alias' => '',
            'menu_ids' => [],
            'api_ids' => [],
        ];
    }

    /**
     * 修改表单字段验证规则结合.
     */
    public function getUpdateFormRules(): array
    {
        return [
            'role_name' => [
                [
                    'required' => true,
                    'message' => '角色名称必填',
                    'trigger' => 'change',
                ],
            ],
            'role_alias' => [
                [
                    'required' => true,
                    'message' => '角色别名必填',
                    'trigger' => 'change',
                ],
            ],
        ];
    }

    /**
     * get SystemMenuRepository.
     */
    public function getSystemMenuRepository(): SystemMenuRepository
    {
        if (empty($this->systemMenuRepository)) {
            $this->systemMenuRepository = make(SystemMenuRepository::class);
        }
        return $this->systemMenuRepository;
    }
}
