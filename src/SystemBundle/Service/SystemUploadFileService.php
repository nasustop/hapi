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
namespace SystemBundle\Service;

use App\Exception\RuntimeErrorException;
use Hyperf\DbConnection\Db;
use Hyperf\HttpMessage\Exception\BadRequestHttpException;
use Hyperf\HttpMessage\Upload\UploadedFile;
use League\Flysystem\Filesystem;
use SystemBundle\Contract\UploadFileInterface;
use SystemBundle\Job\SystemUploadCsvHandleBatchJob;
use SystemBundle\Job\SystemUploadCsvHandleRowJob;
use SystemBundle\Job\SystemUploadCsvReadJob;
use SystemBundle\Repository\SystemUploadFileMessageRepository;
use SystemBundle\Repository\SystemUploadFileRepository;
use SystemBundle\Service\UploadFile\DemoUploadFileService;

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
class SystemUploadFileService
{
    protected SystemUploadFileRepository $repository;

    protected SystemUploadFileMessageRepository $messageRepository;

    protected UploadFileInterface $uploadFile;

    protected Filesystem $filesystem;

    public function __call($method, $parameters)
    {
        return $this->getRepository()->{$method}(...$parameters);
    }

    /**
     * get Repository.
     */
    public function getRepository(): SystemUploadFileRepository
    {
        if (empty($this->repository)) {
            $this->repository = container()->get(SystemUploadFileRepository::class);
        }
        return $this->repository;
    }

    /**
     * get MessageRepository.
     */
    public function getMessageRepository(): SystemUploadFileMessageRepository
    {
        if (empty($this->messageRepository)) {
            $this->messageRepository = container()->get(SystemUploadFileMessageRepository::class);
        }
        return $this->messageRepository;
    }

    public function getUploadFileService(string $fileType): UploadFileInterface
    {
        $this->uploadFile = match ($fileType) {
            'demo' => new DemoUploadFileService(),
            default => throw new BadRequestHttpException('未定义上传类型'),
        };
        return $this->uploadFile;
    }

    /**
     * get Filesystem.
     */
    public function getFilesystem(): Filesystem
    {
        if (empty($this->filesystem)) {
            $this->filesystem = container()->get(Filesystem::class);
        }
        return $this->filesystem;
    }

    /**
     * 生成导入模板.
     */
    public function uploadTemplate(string $fileType, string $fileName): array
    {
        $uploadFileService = $this->getUploadFileService($fileType);
        $headerTitle = $uploadFileService->getHeaderTitle();
        $title = array_column($headerTitle, 'name');

        $row = [];
        for ($i = 0; $i <= count($title); ++$i) {
            $row[] = '';
        }

        $csvData = [
            $title,
            array_merge($row, ['填写说明【上传时请删除】']),
            array_merge($row, ['字段名', '字段类型', '是否必填', '备注']),
        ];
        foreach ($headerTitle as $data) {
            $csvData[] = array_merge($row, [$data['name'], $data['type'], $data['is_need'] ? '是' : '否', $data['remarks']]);
        }

        $csvContent = '';
        foreach ($csvData as $value) {
            $csvContent .= implode(',', $value) . "\n";
        }

        return [
            'name' => $fileName . '.csv',
            'file' => 'data:text/csv;charset=utf-8,' . urlencode($csvContent),
        ];
    }

    /**
     * 导出错误信息文件.
     */
    public function exportHandleErrorFile(int $upload_id): array
    {
        $uploadInfo = $this->getRepository()->getInfoByID($upload_id);
        if (empty($uploadInfo)) {
            throw new BadRequestHttpException('上传记录不存在，无法导出错误信息');
        }
        if ($uploadInfo['handle_status'] !== SystemUploadFileRepository::ENUM_HANDLE_STATUS_FINISH) {
            throw new BadRequestHttpException('上传文件尚未处理完成，请稍后导出错误信息');
        }
        if ($uploadInfo['error_line_num'] <= 0) {
            throw new BadRequestHttpException('没有错误记录');
        }
        if (! empty($uploadInfo['handle_error_path'])) {
            return [
                'path' => $uploadInfo['handle_error_path'],
            ];
        }
        $filter = [
            'upload_id' => $upload_id,
            'handle_status' => SystemUploadFileMessageRepository::ENUM_HANDLE_STATUS_ERROR,
        ];
        $orderBy = ['line_num' => 'asc'];
        $uploadLogList = $this->getMessageRepository()->getLists(filter: $filter, orderBy: $orderBy);
        if (empty($uploadLogList)) {
            throw new BadRequestHttpException('错误信息已被清空，无法下载文件');
        }
        $title = ['错误行', '错误原因'];
        $path = '/uploads/' . $uploadInfo['file_type'] . '/' . date('Y-m-d') . '/';
        $fileDir = storage_path('csv') . $path;
        if (! is_dir($fileDir)) {
            mkdir($fileDir, 0777, true);
        }
        $fileDir = $fileDir . $uploadInfo['file_type'] . '_' . date('H:i:s') . '_error.csv';
        $path = $path . $uploadInfo['file_type'] . '_' . date('H:i:s') . '_error.csv';
        $fh = fopen($fileDir, 'w');
        fwrite($fh, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($fh, $title);
        foreach ($uploadLogList as $value) {
            fputcsv($fh, [$value['line_num'], $value['error_message']]);
        }
        fclose($fh);

        $this->getFilesystem()->write($path, file_get_contents($fileDir));
        $driver = config('file.default');
        $base_uri = config(sprintf('file.storage.%s.domain', $driver));
        $path = $base_uri . $path;

        Db::beginTransaction();
        try {
            $this->getRepository()->updateOneBy(['id' => $upload_id], ['handle_error_path' => $path]);
            $this->getMessageRepository()->deleteBy(['upload_id' => $upload_id]);

            Db::commit();
        } catch (\Exception $exception) {
            Db::rollBack();
            throw $exception;
        }

        return [
            'path' => $path,
        ];
    }

    public function uploadFile(int $user_id, string $fileType, int $fileRelID, UploadedFile $file, int $chunk_num = 500, bool $useQueue = true): array
    {
        $uploadFileService = $this->getUploadFileService($fileType);
        $uploadFileService->checkFile($file);
        $uploadFileService->checkFileRelID($fileRelID);

        $fileName = $file->getClientFilename();
        $path = $uploadFileService->putRawFileDiskPath($fileType, $file);

        $data = [
            'user_id' => $user_id,
            'file_type' => $fileType,
            'file_rel_id' => $fileRelID,
            'file_name' => $fileName,
            'file_size' => $file->getSize(),
            'handle_raw_path' => $path,
        ];
        $upload_id = $this->getRepository()->insertGetId($data);

        $job = new SystemUploadCsvReadJob($upload_id, $chunk_num, $useQueue);
        if ($useQueue) {
            pushQueue($job);
        } else {
            $job->handle();
        }
        return $this->getRepository()->getInfoByID($upload_id);
    }

    public function handleUploadFile(int $id, int $chunk_num, bool $useQueue)
    {
        $uploadInfo = $this->getRepository()->getInfoByID($id);
        if (empty($uploadInfo)) {
            throw new RuntimeErrorException('导入文件记录不存在');
        }

        if ($uploadInfo['handle_status'] !== SystemUploadFileRepository::ENUM_HANDLE_STATUS_WAIT) {
            throw new RuntimeErrorException('导入文件状态已在处理中，请勿重复处理');
        }

        $uploadFileService = $this->getUploadFileService($uploadInfo['file_type']);

        $column = []; // csv标题行对应列的下标
        $title = $uploadFileService->getHeaderTitle();
        $newTitle = []; // 标题汉字下标
        foreach ($title as $key => $value) {
            $newTitle[$value['name']] = $key;
        }
        $readData = [];

        // 打开文件流
        $handle = fopen($uploadInfo['handle_raw_path'], 'r');
        $readLine = 0;
        if ($handle !== false) {
            while (($value = fgetcsv($handle)) !== false) {
                ++$readLine;
                // 读取第一行的标题
                if ($readLine === 1) {
                    foreach ($value as $kk => $vv) {
                        if (isset($newTitle[$vv])) {
                            $column[$newTitle[$vv]] = $kk;
                        }
                    }
                    continue;
                }
                $rowData = []; // 读取的当前行数据
                foreach ($column as $key => $item) {
                    $rowData[$key] = $value[$item];
                }
                $readData[] = [
                    'line_num' => $readLine - 1,
                    'raw_data' => $rowData,
                ]; // 将一行数据添加总数组中
                if (count($readData) >= $chunk_num) {
                    $this->pushReadData($id, $readData, $useQueue);
                    $readData = [];
                }
            }
        }
        if (! empty($readData)) {
            $this->pushReadData($id, $readData, $useQueue);
        }
        fclose($handle);

        $updateData = [
            'handle_line_num' => $readLine - 1,
        ];
        if ($readLine <= 1) {
            $updateData['handle_status'] = SystemUploadFileRepository::ENUM_HANDLE_STATUS_FINISH;
        }
        $this->getRepository()->updateOneBy(['id' => $id], $updateData);
    }

    public function handleUploadFileBatchData(int $upload_id, array $lineNumData, bool $useQueue): bool
    {
        if (empty($lineNumData)) {
            return false;
        }
        $messageList = $this->getMessageRepository()->getLists([
            'upload_id' => $upload_id,
            'line_num' => $lineNumData,
            'handle_status' => SystemUploadFileMessageRepository::ENUM_HANDLE_STATUS_WAIT,
        ]);
        if (empty($messageList)) {
            return false;
        }
        $lineNumData = array_values(array_unique(array_filter(array_column($messageList, 'line_num'))));
        $rawData = array_column($messageList, 'raw_data');

        $info = $this->getRepository()->getInfoByID($upload_id);
        if (empty($info)) {
            return false;
        }
        $uploadFileService = $this->getUploadFileService($info['file_type']);
        if ($uploadFileService->is_batch_handle() !== true) {
            foreach ($lineNumData as $line_num) {
                $job = new SystemUploadCsvHandleRowJob($upload_id, $line_num);
                if ($useQueue) {
                    pushQueue($job);
                } else {
                    $job->handle();
                }
            }
            return true;
        }
        try {
            $uploadFileService->checkHandleData($rawData);
            $uploadFileService->handle($info['file_rel_id'], $rawData);
            $this->getRepository()->increment(['id' => $upload_id], 'success_line_num', count($lineNumData));
            $this->getMessageRepository()->updateBy([
                'upload_id' => $upload_id,
                'line_num' => $lineNumData,
            ], [
                'handle_status' => SystemUploadFileMessageRepository::ENUM_HANDLE_STATUS_SUCCESS,
            ]);
        } catch (\Exception $exception) {
            $this->getRepository()->increment(['id' => $upload_id], 'error_line_num', count($lineNumData));
            $this->getMessageRepository()->updateBy([
                'upload_id' => $upload_id,
                'line_num' => $lineNumData,
            ], [
                'handle_status' => SystemUploadFileMessageRepository::ENUM_HANDLE_STATUS_ERROR,
                'error_message' => $exception->getMessage(),
            ]);
        }
        $this->checkUploadFinish($upload_id);
        return true;
    }

    public function handleUploadFileRowData(int $upload_id, int $line_num): bool
    {
        $messageInfo = $this->getMessageRepository()->getInfo([
            'upload_id' => $upload_id,
            'line_num' => $line_num,
            'handle_status' => SystemUploadFileMessageRepository::ENUM_HANDLE_STATUS_WAIT,
        ]);
        if (empty($messageInfo)) {
            return false;
        }
        $info = $this->getRepository()->getInfoByID($upload_id);
        if (empty($info)) {
            return false;
        }
        $uploadFileService = $this->getUploadFileService($info['file_type']);
        try {
            $uploadFileService->checkHandleData($messageInfo['raw_data']);
            $uploadFileService->handle($info['file_rel_id'], $messageInfo['raw_data']);
            $this->getRepository()->increment(['id' => $upload_id], 'success_line_num', 1);
            $this->getMessageRepository()->updateBy([
                'upload_id' => $upload_id,
                'line_num' => $line_num,
            ], [
                'handle_status' => SystemUploadFileMessageRepository::ENUM_HANDLE_STATUS_SUCCESS,
            ]);
        } catch (\Exception $exception) {
            $this->getRepository()->increment(['id' => $upload_id], 'error_line_num', 1);
            $this->getMessageRepository()->updateBy([
                'upload_id' => $upload_id,
                'line_num' => $line_num,
            ], [
                'handle_status' => SystemUploadFileMessageRepository::ENUM_HANDLE_STATUS_ERROR,
                'error_message' => $exception->getMessage(),
            ]);
        }
        $this->checkUploadFinish($upload_id);
        return true;
    }

    public function checkUploadFinish($upload_id): bool
    {
        $info = $this->getRepository()->getInfoByID($upload_id);
        if (empty($info)) {
            return false;
        }
        if ($info['success_line_num'] + $info['error_line_num'] >= $info['handle_line_num']) {
            $this->getRepository()->updateOneBy(['id' => $upload_id], [
                'handle_status' => SystemUploadFileRepository::ENUM_HANDLE_STATUS_FINISH,
                'finish_time' => date('Y-m-d H:i:s'),
            ]);
        }
        return true;
    }

    protected function pushReadData(int $upload_id, array $readData, bool $useQueue)
    {
        foreach ($readData as $key => $value) {
            $readData[$key]['upload_id'] = $upload_id;
        }
        $this->getMessageRepository()->batchInsert($readData);
        $lineNumData = array_values(array_unique(array_filter(array_column($readData, 'line_num'))));

        $job = new SystemUploadCsvHandleBatchJob($upload_id, $lineNumData, $useQueue);
        if ($useQueue) {
            pushQueue($job);
        } else {
            $job->handle();
        }
    }
}
