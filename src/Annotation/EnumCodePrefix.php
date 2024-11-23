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
 * 错误码类标识.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class EnumCodePrefix extends AbstractAnnotation
{
    // 错误码前缀
    public int $prefixCode;

    // 错误码类描述
    public string  $info;

    /**
     * @param int $prefixCode 错误码前缀，从100开始
     * @param string $info 错误类的描述
     */
    public function __construct(
        int $prefixCode,
        string $info,
    ) {
        $this->prefixCode = $prefixCode;
        $this->info = $info;
    }
}
