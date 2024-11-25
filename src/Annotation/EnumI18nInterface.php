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

interface EnumI18nInterface
{
    /**
     * 获取所有文本内容.
     */
    public function getTxtArr(): ?array;

    /**
     * 获取文本内容.
     */
    public function getTxt(): ?string;

    /**
     * 获取集合编码.
     */
    public function getI18nGroupCode(): ?int;

    /**
     * 获取i18n的内容.
     */
    public function getI18nTxt(?string $key = null): string|array|null;

    /**
     * 获取i18n的组装内容，用于返回.
     */
    public function genI18nTxt(array $i18nParam = []): array|string;
}
