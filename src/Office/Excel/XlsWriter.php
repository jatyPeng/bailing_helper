<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */

namespace Bailing\Office\Excel;

use Bailing\Constants\Code\Common\CommonCode;
use Bailing\Exception\BusinessException;
use Bailing\Helper\StrHelper;
use Bailing\Office\Excel;
use Bailing\Office\Interfaces\ExcelPropertyInterface;
use Hyperf\DbConnection\Model\Model;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Vtiful\Kernel\Format;
use Vtiful\Kernel\Validation;

class XlsWriter extends Excel implements ExcelPropertyInterface
{
    public static function getSheetData(mixed $request): array
    {
        $file = $request->file('file');
        $tempFileName = 'import_' . time() . '.' . $file->getExtension();
        $tempFilePath = RUNTIME_BASE_PATH . '/' . $tempFileName;
        file_put_contents($tempFilePath, $file->getStream()->getContents());
        $xlsxObject = new \Vtiful\Kernel\Excel(['path' => RUNTIME_BASE_PATH . '/']);
        return $xlsxObject->openFile($tempFileName)->openSheet()->getSheetData();
    }

    /**
     * 导入数据.
     */
    public function import(Model $model, ?\Closure $closure = null, int $orgId = 0): bool
    {
        $request = container()->get(RequestInterface::class);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $tempFileName = 'import_' . time() . '_' . mt_rand(10000, 99999) . '.' . $file->getExtension();
            $tempFilePath = RUNTIME_BASE_PATH . '/' . $tempFileName;
            file_put_contents($tempFilePath, $file->getStream()->getContents());
            $xlsxObject = new \Vtiful\Kernel\Excel(['path' => RUNTIME_BASE_PATH . '/']);
            $data = $xlsxObject->openFile($tempFileName)->openSheet()->getSheetData();
            unset($data[0]);

            $importData = [];
            foreach ($data as $item) {
                $tmp = [];
                $errorMsg = '';
                foreach ($item as $key => $value) {
                    $value = StrHelper::mb_trim((string) $value);
                    $tmpProperty = $this->property[$key];
                    $tmp[$tmpProperty['name']] = $value;

                    // 判断必填字段
                    if(empty($errorMsg) && $tmpProperty['required'] && empty($value)){
                        $errorMsg = CommonCode::PARAMS_EMPTY_WITH_FIELD->genI18nMsg(['field' => $tmpProperty['value']], true, $this->nowLang);
                    }

                    // 判断字典值
                    if(!empty($tmpProperty['dictNameArr'])) {
                        stdLog()->info('dictNameArr', [$tmpProperty['dictNameArr'], $value]);
                        if(in_array($value, $tmpProperty['dictNameArr'])){
                            $tmp[$tmpProperty['name']] = array_search($value, $tmpProperty['dictNameArr']);
                        } else if(empty($errorMsg)){
                            $errorMsg = CommonCode::PARAMS_WRONG_WITH_FIELD->genI18nMsg(['field' => $tmpProperty['value']], true, $this->nowLang);
                        }
                    }

                    // 判断字典数组
                    if(!empty($tmpProperty['dictData'])) {
                        if(in_array($value, $tmpProperty['dictData'])){
                            $tmp[$tmpProperty['name']] = array_search($value, $tmpProperty['dictData']);
                        } else if(empty($errorMsg)){
                            $errorMsg = CommonCode::PARAMS_WRONG_WITH_FIELD->genI18nMsg(['field' => $tmpProperty['value']], true, $this->nowLang);
                        }
                    }
                }
                $tmp['result'] = $errorMsg;
                $importData[] = $tmp;
            }

            if ($closure instanceof \Closure) {
                return $closure($model, $importData);
            }

            try {
                foreach ($importData as $item) {
                    $model::create($item);
                }
                @unlink($tempFilePath);
            } catch (\Exception $e) {
                @unlink($tempFilePath);
                throw new \Exception($e->getMessage());
            }
            return true;
        }
        return false;
    }

    /**
     * 导出excel.
     */
    public function export(string $filename, array|\Closure $closure, \Closure $callbackData = null, bool $isDemo = false, int $orgId = 0): \Psr\Http\Message\ResponseInterface
    {
        $filename .= '.xlsx';
        is_array($closure) ? $data = &$closure : $data = $closure();

        $aligns = [
            'left' => Format::FORMAT_ALIGN_LEFT,
            'center' => Format::FORMAT_ALIGN_CENTER,
            'right' => Format::FORMAT_ALIGN_RIGHT,
        ];

        $columnName = [];
        $columnField = [];
        $validationField = [];

        foreach ($this->property as $item) {
            $columnName[] = $item['value'];
            $columnField[] = $item['name'];
        }

        $tempFileName = 'export_' . time() . '.xlsx';
        $xlsxObject = new \Vtiful\Kernel\Excel(['path' => RUNTIME_BASE_PATH . '/']);
        $fileObject = $xlsxObject->fileName($tempFileName)->header($columnName);
        $columnFormat = new Format($fileObject->getHandle());
        $rowFormat = new Format($fileObject->getHandle());

        for ($i = 0; $i < count($columnField); ++$i) {
            $fileObject->setColumn(
                sprintf('%s1:%s1', $this->getColumnIndex($i), $this->getColumnIndex($i)),
                $this->property[$i]['width'] ?? mb_strlen($columnName[$i]) * 5,
                $columnFormat->align($this->property[$i]['align'] ? $aligns[$this->property[$i]['align']] : $aligns['left'])
                    ->background($this->property[$i]['bgColor'] ?? Format::COLOR_WHITE)
                    ->border(Format::BORDER_THIN)
                    ->fontColor($this->property[$i]['color'] ?? Format::COLOR_BLACK)
                    ->toResource()
            );
            // 判断校验字段
            if(!empty($this->property[$i]['dictNameArr'])){
                $validationField[$i] = array_values($this->property[$i]['dictNameArr']);
            } else if(!empty($this->property[$i]['dictData'])){
                $validationField[$i] = array_values($this->property[$i]['dictData']);
            }
        }

        // 表头加样式
        $fileObject->setRow(
            sprintf('A1:%s1', $this->getColumnIndex(count($columnField))),
            $this->property[0]['headHeight'] ?? 20,
            $rowFormat->bold()->toResource()
        );
        for ($i = 0; $i < count($data); ++$i) {
            $fileObject->setRow(
                sprintf('A%s:%s%s', $i + 2, $this->getColumnIndex(count($columnField)), $i + 2),
                $this->property[0]['height'] ?? 20,
                (new Format($fileObject->getHandle()))->border(Format::BORDER_THIN)->toResource()
            );
        }
        for ($i = 0; $i < count($columnField); ++$i) {
            $fileObject->insertText(
                0,
                $i,
                $columnName[$i],
                null,
                (new Format($fileObject->getHandle()))
                    ->bold()
                    ->align(Format::FORMAT_ALIGN_CENTER, Format::FORMAT_ALIGN_VERTICAL_CENTER)
                    ->background($this->property[$i]['headBgColor'] ?? 0x4AC1FF)
                    ->fontColor($this->property[$i]['headColor'] ?? Format::COLOR_BLACK)
                    ->border(Format::BORDER_THIN)
                    ->toResource()
            );
        }

        $exportData = [
            [],
        ];
        foreach ($data as $item) {
            $yield = [];
            if ($callbackData) {
                $item = $callbackData($item);
            }
            foreach ($this->property as $property) {
                foreach ($item as $name => $value) {
                    if ($property['name'] == $name) {
                        if (! empty($property['dictNameArr'])) {
                            $yield[] = $property['dictNameArr'][$value] ?? '';
                        } elseif (! empty($property['dictData'])) {
                            $yield[] = $property['dictData'][$value] ?? '';
                        } elseif (! empty($property['path'])) {
                            $yield[] = data_get($item, $property['path']);
                        } else {
                            $yield[] = $value;
                        }
                        break;
                    }
                }
            }
            $exportData[] = $yield;
        }
        if (! empty($this->demoValue) && $isDemo) {
            $yieldData = [];
            foreach ($this->property as $property) {
                foreach ($this->demoValue as $key => $value) {
                    if ($property['name'] == $key) {
                        $yieldData[] = $value;
                        break;
                    }
                }
            }
            if (! empty($yieldData)) {
                $exportData[] = $yieldData;
            }
        }

        $response = container()->get(ResponseInterface::class);
        $filePath = $fileObject->data($exportData);

        // 判断校验字段
        foreach($validationField as $key => $item) {
            $validation = new Validation();
            $validation = $validation->validationType(Validation::TYPE_LIST)->valueList($item);
            $forRows = max(count($exportData), 22);
            $column = $this->getColumnIndex($key);
            stdLog()->info('$column', [$column]);
            for($i=1; $i<$forRows; $i++){
                $filePath = $filePath->validation($column . $i, $validation->toResource());
            }
        }

        $filePath = $filePath->output();

        $response->download($filePath, $filename);

        ob_start();
        if (copy($filePath, 'php://output') === false) {
            throw new BusinessException(0, '导出数据失败');
        }
        $res = $this->downloadExcel($filename, ob_get_contents());
        ob_end_clean();

        @unlink($filePath);

        return $res;
    }
}
