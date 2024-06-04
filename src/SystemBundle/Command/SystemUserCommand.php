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

namespace SystemBundle\Command;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Input\InputOption;
use SystemBundle\Repository\SystemUserRelAccountRepository;
use SystemBundle\Service\SystemUserService;

#[Command]
class SystemUserCommand extends HyperfCommand
{
    protected array $headers = [
        'user_id',
        'account',
        'user_name',
        'mobile',
        'user_status',
    ];

    public function __construct(protected ContainerInterface $container)
    {
        parent::__construct('hapi:system:user');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('系统管理员相关操作');
        $this->addOption('action', '', InputOption::VALUE_REQUIRED, '操作');
        $this->addUsage('--action info');
        $this->addUsage('--action create');
        $this->addUsage('--action passwd');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle()
    {
        $action = $this->input->getOption('action');
        switch ($action) {
            case 'info':
                $this->getInfo();
                break;
            case 'create':
                $this->createUser();
                break;
            case 'passwd':
                $this->passwd();
                break;
            default:
                $this->error('无效的action');
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getInfo(): bool
    {
        $user_id = $this->ask('请输入 user_id ');
        if (empty($user_id)) {
            $this->error('user_id 不能为空');
            return false;
        }
        $service = $this->container->get(SystemUserService::class);
        $info = $service->getInfo(['user_id' => $user_id]);
        $headers = [];
        $tables = [];
        foreach ($this->headers as $header) {
            if (isset($info[$header])) {
                $headers[] = $header;
                $tables[$header] = $info[$header];
            }
        }
        $this->table($headers, [$tables]);

        return true;
    }

    public function createUser()
    {
        $account = $this->ask('请输入 account ');
        if (empty($account)) {
            $this->error('account 不能为空');
            return false;
        }
        $password = $this->ask('请输入 passwd ');
        if (empty($password)) {
            $this->error('password 不能为空');
            return false;
        }
        $service = $this->container->get(SystemUserService::class);
        $info = $service->createUser([
            'account' => $account,
            'user_name' => $account,
            'password' => $password,
        ]);
        if (empty($info['user_id'])) {
            $this->error('管理员添加失败');
            return false;
        }
        $user = $service->getInfo(['user_id' => $info['user_id']]);
        if (empty($user)) {
            $this->error('管理员添加失败');
            return false;
        }
        $headers = [];
        $tables = [];
        foreach ($this->headers as $header) {
            if (isset($user[$header])) {
                $headers[] = $header;
                $tables[$header] = $user[$header];
            }
        }
        $this->table($headers, [$tables]);
        return true;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function passwd(): bool
    {
        $user_id = $this->ask('请输入 user_id ');
        if (empty($user_id)) {
            $this->error('user_id 不能为空');
            return false;
        }
        $password = $this->ask('请输入 password ');
        if (empty($password)) {
            $this->error('password 不能为空');
            return false;
        }
        $service = $this->container->get(SystemUserService::class);
        $service->getSystemUserRelAccountRepository()->updateOneBy([
                'user_id' => $user_id,
                'rel_type' => SystemUserRelAccountRepository::ENUM_REL_TYPE_ACCOUNT,
            ], ['password' => $password]);
        $user = $service->getInfo(['user_id' => $user_id]);
        if (empty($user)) {
            $this->error('管理员修改失败');
            return false;
        }
        $headers = [];
        $tables = [];
        foreach ($this->headers as $header) {
            if (isset($user[$header])) {
                $headers[] = $header;
                $tables[$header] = $user[$header];
            }
        }
        $this->table($headers, [$tables]);
        return true;
    }
}
