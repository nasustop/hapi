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
use SystemBundle\Repository\SystemRoleRepository;

class SystemUserTemplate extends Template
{
    public function getTableApiUri(): string
    {
        return '/system/auth/user/list';
    }

    public function getTableHeaderCreateActionUri(): string
    {
        return '/system/user/edit';
    }

    public function getTableColumnUpdateActionUri(): string
    {
        return '/system/user/edit';
    }

    public function getTableColumnDeleteActionUri(): string
    {
        return '/system/auth/user/delete';
    }

    public function getCreateFormSaveApiUri(): string
    {
        return '/system/auth/user/create';
    }

    public function getUpdateFormInfoApiUri(): string
    {
        return '/system/auth/user/info';
    }

    public function getUpdateFormSaveApiUri(): string
    {
        return '/system/auth/user/update';
    }

    public function getTableKey(): string
    {
        return 'user_id';
    }

    public function getTableHeaderFilter(): array
    {
        return [
            'account' => [
                'placeholder' => '请输入账号1',
                'clearable' => true,
            ],
            'mobile' => [
                'placeholder' => '请输入手机号',
                'clearable' => true,
            ],
        ];
    }

    public function getTableColumns(): array
    {
        return [
            'user_id' => [
                'title' => '用户ID',
            ],
            'user_name' => [
                'title' => '用户昵称',
            ],
            'avatar_url' => [
                'title' => '用户头像',
                'type' => 'avatar',
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
        ];
    }

    public function getCreateFormColumns(): array
    {
        $menuData = $this->getSystemMenuRepository()->findTreeByMenuIds();
        $menuTree = $menuData['tree'] ?? [];
        $roleData = $this->getSystemRoleRepository()->getLists();
        return [
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
        ];
    }

    public function getCreateFormRuleForm(): array
    {
        return [
            'account' => '',
            'password' => '',
            'avatar_url' => '',
            'user_name' => '',
            'mobile' => '',
            'email' => '',
            'menu_ids' => [],
        ];
    }

    public function getCreateFormRules(): array
    {
        return [
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
        ];
    }

    public function getUpdateFormColumns(): array
    {
        $menuData = $this->getSystemMenuRepository()->findTreeByMenuIds();
        $menuTree = $menuData['tree'] ?? [];
        $roleData = $this->getSystemRoleRepository()->getLists();
        return [
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
        ];
    }

    public function getUpdateFormRuleForm(): array
    {
        return [
            'account' => '',
            'avatar_url' => '',
            'user_name' => '',
            'mobile' => '',
            'email' => '',
            'role_ids' => [],
            'menu_ids' => [],
        ];
    }

    public function getUpdateFormRules(): array
    {
        return [
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
