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
 * 字典类型表.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class DictType extends AbstractAnnotation
{
    // 字典名称
    public string $name = '';

    // 字典名称，i18n格式 {en: 'xxxxx', zh_hk: 'xxxxxx', zh_tw: 'xxxxxx', ja: 'xxxxxx'}
    public ?array  $i18nName = null;

    // 字典类型
    public string $type = '';

    // 所属菜单的别名
    public string $menuAlias = '';

    // 状态（1启用，0停用）
    public int $status = 1;

    // 备注
    public string $remark = '';

    public function __construct(
        string $name = '',
        array $i18nName = [],
        string $type = '',
        string $menuAlias = '',
        int $status = 1,
        string $remark = '',
    ) {
        $this->name = $name;
        $this->i18nName = $i18nName;
        $this->type = $type;
        $this->menuAlias = $menuAlias;
        $this->status = $status;
        $this->remark = $remark;
    }
}
