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

use SystemBundle\Repository\SystemMenuRepository;
use SystemBundle\Repository\SystemRoleRepository;

class SystemUserTemplate
{
    protected SystemMenuRepository $systemMenuRepository;

    protected SystemRoleRepository $systemRoleRepository;

    public function templateList(): array
    {
        return [
            'type' => 'table',
            'header' => [
                'filter' => [
                    'account' => [
                        'placeholder' => '请输入账号',
                    ],
                    'mobile' => [
                        'placeholder' => '请输入手机号',
                    ],
                ],
                'actions' => [
                    'create' => [
                        'title' => '添加',
                        'type' => 'primary',
                        'icon' => 'el-icon-edit',
                        'jump' => true,
                        'url' => [
                            'const' => '/system/user/edit',
                        ],
                    ],
                ],
            ],
            'table' => [
                'url' => [
                    'const' => '/system/auth/user/list',
                ],
                'page' => 1,
                'page_size' => 20,
                'key' => 'user_id',
                'columns' => [
                    'user_id' => [
                        'title' => '用户ID',
                    ],
                    'user_name' => [
                        'title' => '用户昵称',
                    ],
                    'avatar_url' => [
                        'title' => '用户头像',
                        'type' => 'image',
                    ],
                    'account' => [
                        'title' => '用户账号',
                    ],
                    'mobile' => [
                        'title' => '用户手机号',
                    ],
                    'email' => [
                        'title' => '用户邮箱',
                    ],
                ],
                'actions' => [
                    'update' => [
                        'title' => '修改',
                        'jump' => true,
                        'url' => [
                            'const' => '/system/user/edit',
                            'query' => [
                                'user_id' => [
                                    'type' => 'integer',
                                ],
                            ],
                        ],
                    ],
                    'delete' => [
                        'title' => '删除',
                        'confirm' => [
                            'title' => '提示',
                            'message' => '确定要删除嘛？',
                            'type' => 'warning',
                        ],
                        'url' => [
                            'const' => '/system/auth/user/delete',
                            'method' => 'post',
                            'query' => [
                                'user_id' => [
                                    'type' => 'integer',
                                ],
                            ],
                            'notice' => [
                                'success' => [
                                    'title' => '删除成功',
                                    'message' => '删除成功',
                                    'refresh' => true,
                                ],
                                'error' => [
                                    'type' => 'warning',
                                    'title' => '删除失败',
                                    'refresh' => false,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function templateCreate(): array
    {
        $menuData = $this->getSystemMenuRepository()->findTreeByMenuIds();
        $menuTree = $menuData['tree'] ?? [];
        $roleData = $this->getSystemRoleRepository()->getLists();
        return [
            'title' => '添加用户',
            'type' => 'form',
            'form' => [
                'style' => '',
                'labelWidth' => '100px',
                'columns' => [
                    'user_name' => [
                        'type' => 'text',
                        'title' => '用户昵称',
                    ],
                    'avatar_url' => [
                        'type' => 'avatar',
                        'title' => '用户头像',
                    ],
                    'account' => [
                        'type' => 'text',
                        'title' => '用户账号',
                    ],
                    'password' => [
                        'type' => 'password',
                        'title' => '密码',
                        'showPassword' => true,
                    ],
                    'repeat_pwd' => [
                        'type' => 'password',
                        'title' => '确认密码',
                        'showPassword' => true,
                    ],
                    'mobile' => [
                        'type' => 'text',
                        'title' => '用户手机号',
                    ],
                    'email' => [
                        'type' => 'email',
                        'title' => '用户邮箱',
                    ],
                    'role_ids' => [
                        'type' => 'tree',
                        'title' => '角色权限',
                        'data' => $roleData,
                        'dataKey' => 'role_id',
                        'props' => [
                            'children' => 'children',
                            'label' => 'role_name',
                        ],
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
                ],
            ],
            'ruleForm' => [
                'account' => '',
                'password' => '',
                'avatar_url' => '',
                'user_name' => '',
                'mobile' => '',
                'email' => '',
                'menu_ids' => [],
            ],
            'rules' => [
                'account' => [
                    [
                        'required' => true,
                        'message' => '账号必填',
                        'trigger' => 'change',
                    ],
                ],
                'password' => [
                    [
                        'required' => true,
                        'message' => '密码必填',
                        'trigger' => 'change',
                    ],
                ],
                'email' => [
                    [
                        'required' => true,
                        'message' => '邮箱必填',
                    ],
                ],
                'repeat_pwd' => [
                    [
                        'required' => true,
                        'message' => '确认密码必填',
                    ],
                    [
                        'validator' => "(rule, value, callback) => {
                                if (value === '') {
                                  callback(new Error('请再次输入密码'));
                                } else if (value !== this.ruleForm.password) {
                                  callback(new Error('两次输入密码不一致!'));
                                } else {
                                  callback();
                                }
                            }",
                        'trigger' => 'change',
                    ],
                ],
            ],
            'actions' => [
                'cancel' => [
                    'title' => '取消',
                    'action' => 'rollback',
                ],
                'reset' => [
                    'title' => '重置',
                    'action' => 'reset',
                ],
                'create' => [
                    'title' => '确定',
                    'type' => 'primary',
                    'action' => 'request',
                    'url' => [
                        'const' => '/system/auth/user/create',
                        'method' => 'post',
                        'notice' => [
                            'success' => [
                                'title' => '添加成功',
                                'rollback' => true,
                            ],
                            'error' => [
                                'title' => '添加失败',
                                'rollback' => false,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function templateUpdate(): array
    {
        $menuData = $this->getSystemMenuRepository()->findTreeByMenuIds();
        $menuTree = $menuData['tree'] ?? [];
        $roleData = $this->getSystemRoleRepository()->getLists();
        return [
            'title' => '修改用户',
            'type' => 'form',
            'form' => [
                'style' => '',
                'labelWidth' => '100px',
                'columns' => [
                    'account' => [
                        'type' => 'text',
                        'title' => '用户账号',
                        'disabled' => true,
                    ],
                    'mobile' => [
                        'type' => 'text',
                        'title' => '用户手机号',
                    ],
                    'email' => [
                        'type' => 'email',
                        'title' => '用户邮箱',
                    ],
                    'user_name' => [
                        'type' => 'text',
                        'title' => '用户昵称',
                    ],
                    'avatar_url' => [
                        'type' => 'avatar',
                        'title' => '用户头像',
                    ],
                    'role_ids' => [
                        'type' => 'tree',
                        'title' => '角色权限',
                        'data' => $roleData,
                        'dataKey' => 'role_id',
                        'props' => [
                            'children' => 'children',
                            'label' => 'role_name',
                        ],
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
                ],
                'uri' => [
                    'const' => '/system/auth/user/info',
                    'method' => 'get',
                ],
            ],
            'ruleForm' => [
                'account' => '',
                'avatar_url' => '',
                'user_name' => '',
                'mobile' => '',
                'email' => '',
                'role_ids' => [],
                'menu_ids' => [],
            ],
            'rules' => [
                'account' => [
                    [
                        'required' => true,
                        'message' => '账号必填',
                        'trigger' => 'change',
                    ],
                ],
                'email' => [
                    [
                        'required' => true,
                        'message' => '邮箱必填',
                    ],
                ],
                'mobile' => [
                    [
                        'required' => true,
                        'message' => '手机号必填',
                    ],
                ],
            ],
            'actions' => [
                'cancel' => [
                    'title' => '取消',
                    'action' => 'rollback',
                ],
                'reset' => [
                    'title' => '重置',
                    'action' => 'reset',
                ],
                'update' => [
                    'title' => '确定',
                    'type' => 'primary',
                    'action' => 'request',
                    'url' => [
                        'const' => '/system/auth/user/update',
                        'method' => 'post',
                        'notice' => [
                            'success' => [
                                'title' => '修改成功',
                                'rollback' => true,
                            ],
                            'error' => [
                                'title' => '修改失败',
                                'rollback' => false,
                            ],
                        ],
                    ],
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

    /**
     * get SystemRoleRepository.
     */
    public function getSystemRoleRepository(): SystemRoleRepository
    {
        if (empty($this->systemRoleRepository)) {
            $this->systemRoleRepository = make(SystemRoleRepository::class);
        }
        return $this->systemRoleRepository;
    }
}
