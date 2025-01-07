<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */

namespace Bailing\Office\Annotation;

use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * excel导入导出元数据。
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class ExcelProperty extends AbstractAnnotation
{
    /**
     * 列表头名称.
     */
    public string $value;

    /**
     * 列表头名称（国际化）.
     */
    public array $i18nValue;

    /**
     * 列表示例数据.
     */
    public string $demo;

    /**
     * 列顺序.
     */
    public int $index;

    /**
     * 宽度.
     */
    public int $width;

    /**
     * 高度（仅取第一个的）.
     */
    public int $height;

    /**
     * 对齐方式，默认居左.
     */
    public string $align;

    /**
     * 列表头是否必填.
     */
    public bool $required;

    /**
     * 列表头的高度（仅取第一个的）.
     */
    public int $headHeight;

    /**
     * 列表体字体颜色.
     */
    public int|string $color;

    /**
     * 列表体背景颜色.
     */
    public int|string $bgColor;

    /**
     * 字典数据列表.
     */
    public ?array $dictData = null;

    /**
     * 字典名称.
     */
    public string $dictName;

    /**
     * 数据路径 用法: object.value.
     */
    public string $path;

    public function __construct(
        string $value,
        int $index,
        string $demo = '',
        array $i18nValue = [],
        int $width = null,
        int $height = null,
        string $align = null,
        bool $required = false,
        int $headHeight = null,
        int|string $color = null,
        int|string $bgColor = null,
        array $dictData = null,
        string $dictName = null,
        string $path = null,
    ) {
        $this->value = $value;
        $this->index = $index;
        $this->required = $required;
        isset($demo) && $this->demo = $demo;
        isset($i18nValue) && $this->i18nValue = $i18nValue;
        isset($width) && $this->width = $width;
        isset($height) && $this->height = $height;
        isset($align) && $this->align = $align;
        isset($headHeight) && $this->headHeight = $headHeight;
        isset($color) && $this->color = $color;
        isset($bgColor) && $this->bgColor = $bgColor;
        isset($dictData) && $this->dictData = $dictData;
        isset($dictName) && $this->dictName = $dictName;
        isset($path) && $this->path = $path;
    }
}
