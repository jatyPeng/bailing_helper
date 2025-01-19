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

use Bailing\Constants\I18n\Common\CommonI18n;
use Bailing\Exception\BusinessException;
use Bailing\Helper\Intl\I18nHelper;
use Bailing\JsonRpc\Org\OrgUserServiceInterface;
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

    protected bool $isDemo = false;

    protected string $nowLang = '';

    protected int $orgId = 0;

    public function __construct(string $dto, array $extraData = [], bool $isDemo = false, int $orgId = 0)
    {
        if (! (new $dto()) instanceof ModelExcelInterface) {
            throw new BusinessException(0, 'Dto does not implement an interface of the MineModelExcel');
        }

        $dtoObject = new $dto();
        if (method_exists($dtoObject, 'dictData')) {
            $this->dictData = $dtoObject->dictData();
        }
        $this->orgId = $orgId;
        $this->annotationMate = AnnotationCollector::get($dto);
        if (! empty($extraData)) {
            if (! empty($this->annotationMate['_c'])) {
                $startIndex = count($this->annotationMate['_p']) - 1;
                foreach ($extraData as $key => $value) {
                    ++$startIndex;
                    $dataObj = new ExcelProperty(
                        value: $value['fields_name'],
                        index: $startIndex,
                        demo: $value['fields_demo'] ?? '',
                        tip: $value['fields_tip'] ?? '',
                        i18nValue:  $value['i18n_fields_name']['i18n_value'] ?? [],
                        i18nDemo:  $value['i18n_fields_demo']['i18n_value'] ?? [],
                        i18nTip:  $value['i18n_fields_tip']['i18n_value'] ?? [],
                        width: 20,
                        align: 'left',
                        required: (bool) $value['fill'],
                        dictName: $value['dictName'] ?? '',
                        dictData: $value['dictData'] ?? [],
                    );
                    $this->annotationMate['_p'][$value['key']][self::ANNOTATION_NAME] = $dataObj;
                }
            }
        }

        // 拼接导入结果字段
        if (!$isDemo) {
            $i18nResult = CommonI18n::IMPORT_RESULT->genI18nTxt();
            $this->annotationMate['_p']['result'][self::ANNOTATION_NAME] = new ExcelProperty(
                value: '导入结果',
                index: count($this->annotationMate['_p']),
                i18nValue:  $i18nResult['i18n_value'],
                width: 25,
                align: 'left',
                required: false,
            );
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

        $this->nowLang = I18nHelper::getNowLang();

        foreach ($this->annotationMate['_p'] as $name => $mate) {
            $value = $mate[self::ANNOTATION_NAME]->i18nValue[$this->nowLang] ?? $mate[self::ANNOTATION_NAME]->value;
            $tip = $mate[self::ANNOTATION_NAME]->i18nTip[$this->nowLang] ?? $mate[self::ANNOTATION_NAME]->tip;
            // 英文、日语环境下，宽度放大0.4倍
            $width = ! empty($mate[self::ANNOTATION_NAME]->width) ? (in_array($this->nowLang, ['en', 'ja']) ? intval($mate[self::ANNOTATION_NAME]->width * 1.4) : $mate[self::ANNOTATION_NAME]->width) : null;
            $this->property[$mate[self::ANNOTATION_NAME]->index] = [
                'name' => $name,
                'value' => $value,
                'tip' => $tip,
                'width' => $width,
                'height' => $mate[self::ANNOTATION_NAME]->height ?? null,
                'align' => $mate[self::ANNOTATION_NAME]->align ?? null,
                'headColor' => Format::COLOR_WHITE,
                'headBgColor' => $mate[self::ANNOTATION_NAME]->required ? Format::COLOR_RED : 0x5A5A5A,
                'headHeight' => $mate[self::ANNOTATION_NAME]->headHeight ?? null,
                'color' => $mate[self::ANNOTATION_NAME]->color ?? null,
                'bgColor' => $mate[self::ANNOTATION_NAME]->bgColor ?? null,
                'dictName' => $mate[self::ANNOTATION_NAME]->dictName ?? '',
                'required' => $mate[self::ANNOTATION_NAME]->required ?? false,
            ];

            $this->demoValue[$name] = $mate[self::ANNOTATION_NAME]->i18nDemo[$this->nowLang] ?? $mate[self::ANNOTATION_NAME]->demo;
        }

        // 批量替换字典
        $dictNameArr = arrayColumnUnique($this->property, 'dictName');
        if(!empty($dictNameArr)){
            $dictResult = container()->get(OrgUserServiceInterface::class)->call('getSystemDictData', ['org_id' => $this->orgId, 'typeArr' => $dictNameArr]);
            if(empty($dictResult['data']['list'])){
                throw new \Exception('Dict is empty, please check');
            }
            $dictResultArr = [];
            foreach($dictResult['data']['list'] as $datum){
                $dictResultArr[$datum['dict_type']][$datum['value']] = $datum['i18n_label']['i18n_value'][$this->nowLang] ?? $datum['label'];
            }
            foreach($this->property as &$propertyItem){
                if(!empty($propertyItem['dictName']) && !empty($dictResultArr[$propertyItem['dictName']])){
                    $propertyItem['dictNameArr'] = $dictResultArr[$propertyItem['dictName']];
                }
            }
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
