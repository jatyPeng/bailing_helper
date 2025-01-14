<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Constants\I18n\Common;

use Bailing\Annotation\EnumI18n;
use Bailing\Annotation\EnumI18nGroup;
use Bailing\Annotation\EnumI18nInterface;
use Bailing\Trait\EnumI18nGet;

#[EnumI18nGroup(groupCode: 'common', info: '公共类')]
enum CommonI18n: int  implements EnumI18nInterface
{
    use EnumI18nGet;

    #[EnumI18n(txt: '导入结果', i18nTxt: ['en' => 'Import Result', 'zh_tw' => '導入結果', 'zh_hk' => '導入結果', 'ja' => '導入結果'])]
    case IMPORT_RESULT = 1;
}
