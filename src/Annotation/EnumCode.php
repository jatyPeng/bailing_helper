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
 * 错误码.
 */
#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class EnumCode extends AbstractAnnotation
{
    // 中文错误提示
    public string $msg = '';

    // 错误提示，i18n格式 {en: 'xxxxx', zh_hk: 'xxxxxx', zh_tw: 'xxxxxx', ja: 'xxxxxx'}
    public ?array  $i18nMsg = null;

    /**
     * @param string $msg 中文错误提示
     * @param ?array $i18nMsg 错误提示，i18n格式 {en: 'xxxxx', zh_hk: 'xxxxxx', zh_tw: 'xxxxxx', ja: 'xxxxxx'}
     */
    public function __construct(
        string $msg,
        ?array $i18nMsg = null,
    ) {
        $this->msg = $msg;
        $this->i18nMsg = $i18nMsg;
    }
}
