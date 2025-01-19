<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */

namespace Bailing\Office;

use Bailing\Constants\Code\Common\CommonCode;
use Bailing\Helper\ApiHelper;
use Bailing\Office\Excel\PhpOffice;
use Bailing\Office\Excel\XlsWriter;
use Hyperf\Codec\Json;
use Hyperf\DbConnection\Model\Model;
use Psr\Http\Message\ResponseInterface;

class Collection extends \Hyperf\Collection\Collection
{
    public function export(string $dto, string $filename, array|\Closure $closure = null, array $extra = [], bool $isDemo = false, int $orgId = 0): ResponseInterface
    {
        $excelDrive = \Hyperf\Config\config('excel.drive', 'auto');
        if ($excelDrive === 'auto') {
            $excel = extension_loaded('xlswriter') ? new XlsWriter($dto, $extra, $isDemo, $orgId) : new PhpOffice($dto);
        } else {
            $excel = $excelDrive === 'xlsWriter' ? new XlsWriter($dto, $extra, $isDemo, $orgId) : new PhpOffice($dto);
        }

        return $excel->export($filename, is_null($closure) ? $this->toArray() : $closure, null, $isDemo, $orgId);
    }

    public function import(string $dto, Model $model, ?\Closure $closure = null, array $extra = [], int $orgId = 0): bool
    {
        $excelDrive = \Hyperf\Config\config('excel.drive', 'auto');
        if ($excelDrive === 'auto') {
            $excel = extension_loaded('xlswriter') ? new XlsWriter($dto, $extra, false, $orgId) : new PhpOffice($dto);
        } else {
            $excel = $excelDrive === 'xlsWriter' ? new XlsWriter($dto, $extra, false, $orgId) : new PhpOffice($dto);
        }
        return $excel->import($model, $closure, $orgId);
    }

    /**
     * 错误内容导出.
     * @param string $dto
     * @param string $errorFileKey
     * @param string $filename
     * @return array|ResponseInterface
     */
    public function downloadImportErrorData(string $dto, string $errorFileKey, string $filename, int $orgId = 0): array|ResponseInterface
    {
        if (empty($errorFileKey)) {
            throw new \Exception(CommonCode::IMPORT_FILE_ID_EMPTY->genI18nMsg(returnNowLang: true));
        }

        $data = redis()->get($errorFileKey);
        if (empty($data)) {
            throw new \Exception(CommonCode::IMPORT_FILE_EXPIRED->genI18nMsg(returnNowLang: true));
        }

        return $this->export($dto, $filename . '-' . date('Ymd-His'), Json::decode($data), [], false, $orgId);
    }
}
