<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * 国际化文本.
 */
#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class EnumI18n extends AbstractAnnotation
{
    // 中文内容
    public string $txt = '';

    // 国际化内容，i18n格式 {en: 'xxxxx', zh_hk: 'xxxxxx', zh_tw: 'xxxxxx', ja: 'xxxxxx'}
    public ?array  $i18nTxt = null;

    /**
     * @param string $txt 中文内容
     * @param ?array $i18nTxt 国际化内容，i18n格式 {en: 'xxxxx', zh_hk: 'xxxxxx', zh_tw: 'xxxxxx', ja: 'xxxxxx'}
     */
    public function __construct(
        string $txt,
        ?array $i18nTxt = null,
    ) {
        $this->txt = $txt;
        $this->i18nTxt = $i18nTxt;
    }
}
