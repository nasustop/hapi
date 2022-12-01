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
use SystemBundle\Service\SystemUserService;

#[Command]
class SystemUserCommand extends HyperfCommand
{
    protected array $headers = [
        'user_id',
        'login_name',
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
        $this->addOption('user_id', '', InputOption::VALUE_OPTIONAL, 'user_id');
        $this->addOption('login_name', '', InputOption::VALUE_OPTIONAL, 'login_name');
        $this->addOption('mobile', '', InputOption::VALUE_OPTIONAL, 'mobile');
        $this->addOption('password', '', InputOption::VALUE_OPTIONAL, 'password');
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
        $user_id = $this->input->getOption('user_id');
        if (empty($user_id)) {
            $this->error('user_id 不能为空');
            return false;
        }
        $service = $this->container->get(SystemUserService::class);
        $info = $service->getInfo(['user_id' => $user_id], $this->headers);
        $this->table($this->headers, [$info]);

        return true;
    }

    public function createUser()
    {
        $login_name = $this->input->getOption('login_name');
        if (empty($login_name)) {
            $this->error('login_name 不能为空');
            return false;
        }
        $mobile = $this->input->getOption('mobile');
        if (empty($mobile)) {
            $this->error('mobile 不能为空');
            return false;
        }
        $password = $this->input->getOption('password');
        if (empty($password)) {
            $this->error('password 不能为空');
            return false;
        }
        $service = $this->container->get(SystemUserService::class);
        $info = $service->createUser([
            'login_name' => $login_name,
            'user_name' => $login_name,
            'mobile' => $mobile,
            'password' => $password,
        ]);
        if (empty($info['user_id'])) {
            $this->error('管理员添加失败');
            return false;
        }
        $user = $service->getInfo(['user_id' => $info['user_id']], $this->headers);
        if (empty($user)) {
            $this->error('管理员添加失败');
            return false;
        }
        $this->table($this->headers, [$user]);
        return true;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function passwd(): bool
    {
        $user_id = $this->input->getOption('user_id');
        if (empty($user_id)) {
            $this->error('user_id 不能为空');
            return false;
        }
        $password = $this->input->getOption('password');
        if (empty($password)) {
            $this->error('password 不能为空');
            return false;
        }
        $service = $this->container->get(SystemUserService::class);
        $service->updateOneBy(['user_id' => $user_id], ['password' => $password]);
        $user = $service->getInfo(['user_id' => $user_id], $this->headers);
        if (empty($user)) {
            $this->error('管理员修改失败');
            return false;
        }
        $this->table($this->headers, [$user]);
        return true;
    }
}
