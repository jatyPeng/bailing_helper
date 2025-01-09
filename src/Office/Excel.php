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

use Bailing\Exception\BusinessException;
use Bailing\Helper\Intl\I18nHelper;
use Bailing\Office\Annotation\ExcelProperty;
use Bailing\Office\Interfaces\ModelExcelInterface;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Vtiful\Kernel\Format;

abstract class Excel
{
    public const ANNOTATION_NAME = 'Bailing\Office\Annotation\ExcelProperty';

    protected ?array $annotationMate;

    protected array $property = [];

    protected array $dictData = [];

    protected array $demoValue = [];

    public function __construct(string $dto, array $extraData = [])
    {
        if (! (new $dto()) instanceof ModelExcelInterface) {
            throw new BusinessException(0, 'Dto does not implement an interface of the MineModelExcel');
        }

        $dtoObject = new $dto();
        if (method_exists($dtoObject, 'dictData')) {
            $this->dictData = $dtoObject->dictData();
        }
        $this->annotationMate = AnnotationCollector::get($dto);
        if (! empty($extraData)) {
            if (! empty($this->annotationMate['_c'])) {
                $startIndex = count($this->annotationMate['_p']) - 1;
                foreach ($extraData as $key => $value) {
                    ++$startIndex;
                    $dataObj = new ExcelProperty(
                        value: $value['fields_name'],
                        index: $startIndex,
                        i18nValue:  $value['i18n_fields_name']['i18n_value'] ?? [],
                        width: 20,
                        align: 'left',
                        required: (bool) $value['fill'],
                    );
                    $this->annotationMate['_p'][$value['key']][self::ANNOTATION_NAME] = $dataObj;
                }
            }
        }
        $this->parseProperty();
    }

    public function getProperty(): array
    {
        return $this->property;
    }

    public function getAnnotationInfo(): array
    {
        return $this->annotationMate;
    }

    protected function parseProperty(): void
    {
        if (empty($this->annotationMate) || ! isset($this->annotationMate['_c'])) {
            throw new BusinessException(0, 'Dto annotation info is empty');
        }

        $nowLang = I18nHelper::getNowLang();

        foreach ($this->annotationMate['_p'] as $name => $mate) {
            $value = $mate[self::ANNOTATION_NAME]->i18nValue[$nowLang] ?? $mate[self::ANNOTATION_NAME]->value;
            // 英文、日语环境下，宽度放大0.4倍
            $width = ! empty($mate[self::ANNOTATION_NAME]->width) ? (in_array($nowLang, ['en', 'ja']) ? intval($mate[self::ANNOTATION_NAME]->width * 1.4) : $mate[self::ANNOTATION_NAME]->width) : null;
            $this->property[$mate[self::ANNOTATION_NAME]->index] = [
                'name' => $name,
                'value' => $value,
                'width' => $width,
                'height' => $mate[self::ANNOTATION_NAME]->height ?? null,
                'align' => $mate[self::ANNOTATION_NAME]->align ?? null,
                'headColor' => Format::COLOR_WHITE,
                'headBgColor' => $mate[self::ANNOTATION_NAME]->required ? Format::COLOR_RED : 0x5A5A5A,
                'headHeight' => $mate[self::ANNOTATION_NAME]->headHeight ?? null,
                'color' => $mate[self::ANNOTATION_NAME]->color ?? null,
                'bgColor' => $mate[self::ANNOTATION_NAME]->bgColor ?? null,
                'dictData' => $mate[self::ANNOTATION_NAME]->dictData,
                'dictName' => empty($mate[self::ANNOTATION_NAME]->dictName) ? null : $this->getDictData($mate[self::ANNOTATION_NAME]->dictName),
                'path' => $mate[self::ANNOTATION_NAME]->path ?? null,
            ];
            $this->demoValue[$name] = $mate[self::ANNOTATION_NAME]->demo ?? '';
        }
        ksort($this->property);
    }

    /**
     * 下载excel.
     */
    protected function downloadExcel(string $filename, string $content): ResponseInterface
    {
        return $response = contextGet(ResponseInterface::class)
            ->withHeader('Server', 'Bailing')
            ->withHeader('access-control-expose-headers', 'content-disposition')
            ->withHeader('content-description', 'File Transfer')
            ->withHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->withHeader('content-disposition', "attachment; filename={$filename}; filename*=UTF-8''" . rawurlencode($filename))
            ->withHeader('content-transfer-encoding', 'binary')
            ->withHeader('pragma', 'public')
            ->withBody(new SwooleStream($content));
    }

    /**
     * 获取字典数据.
     */
    protected function getDictData(string $dictName): array
    {
        $data = [];
        // todo 数据字典
        //        $dictData = container()->get(SystemDictDataService::class)->getList(['code' => $dictName]);
        $dictData = [];
        foreach ($dictData as $item) {
            $data[$item['key']] = $item['title'];
        }

        return $data;
    }

    /**
     * 获取 excel 列索引.
     */
    protected function getColumnIndex(int $columnIndex = 0): string
    {
        if ($columnIndex < 26) {
            return chr(65 + $columnIndex);
        }
        if ($columnIndex < 702) {
            return chr(64 + intval($columnIndex / 26)) . chr(65 + $columnIndex % 26);
        }
        return chr(64 + intval(($columnIndex - 26) / 676)) . chr(65 + intval((($columnIndex - 26) % 676) / 26)) . chr(65 + $columnIndex % 26);
    }
}
