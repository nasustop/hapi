<?php

declare(strict_types=1);

namespace GoodsBundle\Service;

use GoodsBundle\Repository\GoodsParamRepository;
use GoodsBundle\Repository\GoodsParamsValueRepository;
use Hyperf\DbConnection\Db;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;

/**
 * @method getInfo(array $filter, array|string $columns = '*', array $orderBy = [])
 * @method getLists(array $filter = [], array|string $columns = '*', int $page = 0, int $pageSize = 0, array $orderBy = [])
 * @method count(array $filter)
 * @method pageLists(array $filter = [], array|string $columns = '*', int $page = 1, int $pageSize = 100, array $orderBy = [])
 * @method insert(array $data)
 * @method batchInsert(array $data)
 * @method saveData(array $data)
 * @method updateBy(array $filter, array $data)
 * @method updateOneBy(array $filter, array $data)
 * @method deleteBy(array $filter)
 * @method deleteOneBy(array $filter)
 */
class GoodsParamService
{
    protected GoodsParamRepository $repository;

    protected GoodsParamsValueRepository $goodsParamsValueRepository;

    public function __call($method, $parameters)
    {
        return $this->getRepository()->{$method}(...$parameters);
    }

    /**
     * get Repository.
     */
    public function getRepository(): GoodsParamRepository
    {
        if (empty($this->repository)) {
            $this->repository = make(GoodsParamRepository::class);
        }
        return $this->repository;
    }

    /**
     * get GoodsParamsValueRepository
     *
     * @return GoodsParamsValueRepository
     */
    public function getGoodsParamsValueRepository(): GoodsParamsValueRepository
    {
        if (empty($this->goodsParamsValueRepository)) {
            $this->goodsParamsValueRepository = make(GoodsParamsValueRepository::class);
        }
        return $this->goodsParamsValueRepository;
    }

    public function getGoodsParamsInfo($filter): array
    {
        $result = $this->getRepository()->getInfo($filter);
        if (! empty($result)) {
            $result['params_value'] = $this->getGoodsParamsValueRepository()
                ->getLists(['params_id' => $result['params_id']]);
        }
        return $result;
    }

    public function createGoodsParams($data): array
    {
        Db::beginTransaction();
        try {
            $params_id = $this->getRepository()->insertGetId($data);
            $batchInsertData = [];
            foreach ($data['params_value'] ?? [] as $value) {
                $value['params_id'] = $params_id;
                $batchInsertData[] = $value;
            }
            if (! empty($batchInsertData)) {
                $this->getGoodsParamsValueRepository()->batchInsert($batchInsertData);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            throw $e;
        }
        return $this->getGoodsParamsInfo(['params_id' => $params_id]);
    }

    public function updateGoodsParams($filter, $data)
    {
        $info = $this->getRepository()->getInfo($filter);
        if (empty($info)) {
            throw new BadRequestHttpException('无效的商品参数');
        }
        Db::beginTransaction();
        try {
            $this->getRepository()->updateOneBy(['params_id' => $info['params_id']], $data);
            $oldValueData = $this->getGoodsParamsValueRepository()->getLists(['params_id' => $info['params_id']]);
            $oldValueIds = array_column($oldValueData, 'params_value_id');
            $batchInsertData = [];
            $updateValueIds = [];
            foreach ($data['params_value'] ?? [] as $value) {
                if (empty($value['params_value_id'])) {
                    $value['params_id'] = $info['params_id'];
                    $batchInsertData[] = $value;
                } else {
                    $updateValueIds[] = $value['params_value_id'];
                    $this->getGoodsParamsValueRepository()->updateOneBy([
                        'params_value_id' => $value['params_value_id'],
                    ], [
                        'params_value_name' => $value['params_value_name'],
                    ]);
                }
            }
            if (! empty($batchInsertData)) {
                $this->getGoodsParamsValueRepository()->batchInsert($batchInsertData);
            }
            $deleteValueIds = array_diff($oldValueIds, $updateValueIds);
            if (! empty($deleteValueIds)) {
                $this->getGoodsParamsValueRepository()->deleteBy(['params_value_id' => $deleteValueIds]);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            throw $e;
        }
        return $this->getGoodsParamsInfo(['params_id' => $info['params_id']]);
    }
}
