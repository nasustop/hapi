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
namespace SystemBundle\Controller\Backend;

use App\Controller\AbstractController;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Hyperf\Validation\Rule;
use Psr\Http\Message\ResponseInterface;
use SystemBundle\Service\SystemMenuService;
use SystemBundle\Service\SystemRoleService;
use SystemBundle\Service\SystemUserService;

class SystemUserController extends AbstractController
{
    protected SystemUserService $service;

    public function actionSchemaList(): ResponseInterface
    {
        $schema = [
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
        return $this->getResponse()->success($schema);
    }

    public function actionSchemaCreate(): ResponseInterface
    {
        $menuData = (new SystemMenuService())->getRepository()->findTreeByMenuIds();
        $menuTree = $menuData['tree'] ?? [];
        $roleData = (new SystemRoleService())->getLists();
        $schema = [
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
        return $this->getResponse()->success($schema);
    }

    public function actionSchemaUpdate(): ResponseInterface
    {
        $menuData = (new SystemMenuService())->getRepository()->findTreeByMenuIds();
        $menuTree = $menuData['tree'] ?? [];
        $roleData = (new SystemRoleService())->getLists();
        $schema = [
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
        return $this->getResponse()->success($schema);
    }

    public function actionCreate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
            'user_name' => 'required',
            'avatar_url' => 'url',
            'password' => 'required',
            'account' => 'required|unique:system_user_rel_account,rel_type',
            'email' => 'email|unique:system_user_rel_account,rel_type',
            'mobile' => 'unique:system_user_rel_account,rel_type',
        ];
        $messages = [
            'user_name.required' => '用户名称必填',
            'avatar_url.url' => '头像格式错误',
            'password.required' => '密码必填',
            'account.required' => '登陆账号必填',
            'account.unique' => '账号已存在，请更换账号',
            'email.email' => '请输入正确的邮箱格式',
            'email.unique' => '邮箱已存在，请更换邮箱',
            'mobile.unique' => '手机号已存在，请更换手机号',
        ];
        $validator = $this->getValidatorFactory()->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->getService()->createUser(data: $params);

        return $this->getResponse()->success(data: $result);
    }

    public function actionUpdate(): ResponseInterface
    {
        $params = $this->getRequest()->all();

        $rules = [
            'filter' => 'required|array',
            'filter.user_id' => 'required',
            'params' => 'required|array',
            'params.avatar_url' => 'url',
            'params.account' => ['required', Rule::unique('system_user_rel_account', 'rel_type')->ignore($params['filter']['user_id'], 'user_id')],
            'params.email' => ['email', Rule::unique('system_user_rel_account', 'rel_type')->ignore($params['filter']['user_id'], 'user_id')],
            'params.mobile' => Rule::unique('system_user_rel_account', 'rel_type')->ignore($params['filter']['user_id'], 'user_id'),
        ];
        $messages = [
            'filter.required' => 'filter 参数必填',
            'filter.array' => 'filter 参数错误，必须是数组格式',
            'filter.user_id.required' => 'filter.user_id 参数必填',
            'params.required' => 'params 参数必填',
            'params.avatar_url.url' => '头像格式错误',
            'params.account.required' => '登陆账号必填',
            'params.account.unique' => '账号已存在，请更换账号',
            'params.email.email' => '请输入正确的邮箱格式',
            'params.email.unique' => '邮箱已存在，请更换邮箱',
            'params.mobile.unique' => '手机号已存在，请更换手机号',
        ];
        $validator = $this->getValidatorFactory()->make(data: $params, rules: $rules, messages: $messages);

        if ($validator->fails()) {
            throw new BadRequestHttpException(message: $validator->errors()->first());
        }

        $result = $this->getService()->updateUser(filter: $params['filter'], data: $params['params']);

        return $this->getResponse()->success(data: $result);
    }

    public function actionDelete(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $result = $this->getService()->deleteUser(filter: $filter);

        return $this->getResponse()->success(data: $result);
    }

    public function actionInfo(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $result = $this->getService()->getInfo($filter);
        return $this->getResponse()->success($result);
    }

    public function actionList(): ResponseInterface
    {
        $filter = $this->getRequest()->all();
        $page = (int) $this->getRequest()->input(key: 'page', default: 1);
        $page_size = (int) $this->getRequest()->input(key: 'page_size', default: 20);
        $result = $this->getService()->pageUserLists(filter: $filter, columns: '*', page: $page, pageSize: $page_size);

        return $this->getResponse()->success(data: $result);
    }

    /**
     * get Service.
     */
    protected function getService(): SystemUserService
    {
        if (empty($this->service)) {
            $this->service = make(SystemUserService::class);
        }
        return $this->service;
    }
}
