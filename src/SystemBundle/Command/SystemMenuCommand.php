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
use Hyperf\DbConnection\Db;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Input\InputOption;
use SystemBundle\Service\SystemMenuService;

#[Command]
class SystemMenuCommand extends HyperfCommand
{
    public function __construct(protected ContainerInterface $container)
    {
        parent::__construct('hapi:system:menu');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('管理后台菜单的导入导出');
        $this->addOption('action', '', InputOption::VALUE_REQUIRED, '操作');
        $this->addOption('menu_version', '', InputOption::VALUE_OPTIONAL, 'menu_version', 'all');
        $this->addUsage('--action export');
        $this->addUsage('--action upload');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle()
    {
        $action = $this->input->getOption('action');
        switch ($action) {
            case 'export':
                $this->export();
                break;
            case 'upload':
                $this->upload();
                break;
            default:
                $this->error('无效的action');
        }
    }

    protected function getFile($menuVersion): string
    {
        return storage_path('statics') . '/menu_' . $menuVersion . '.json';
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function export()
    {
        $service = $this->container->get(SystemMenuService::class);
        $filter = [];
        $menuVersion = $this->input->getOption('menu_version');
        $menuList = $service->getLists($filter);
        foreach ($menuList as $key => $value) {
            unset($value['created_at_timestamp'], $value['updated_at_timestamp']);
            $menuList[$key] = $value;
        }
        file_put_contents($this->getFile($menuVersion), json_encode($menuList, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $this->info('导出成功');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \Exception
     */
    protected function upload()
    {
        $menuVersion = $this->input->getOption('menu_version');
        $data = file_get_contents($this->getFile($menuVersion));
        $data = @json_decode($data, true);
        if ($data == false) {
            $this->error($this->getFile($menuVersion) . ' 解析失败');
            return;
        }
        Db::beginTransaction();
        try {
            $service = $this->container->get(SystemMenuService::class);
            $filter = [];
            $service->deleteBy($filter);
            $service->batchInsert($data);

            Db::commit();
        } catch (\Exception $exception) {
            Db::rollBack();
            $this->error($exception->getMessage());
            throw $exception;
        }
        $this->info('导入成功');
    }
}
