<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Helper;

use Hyperf\Codec\Json;

class ConfigHelper
{
    /**
     * 按名称获取配置项值.
     * @param string $name 配置项的名称
     */
    public static function getConfig(string $name)
    {
        $configArr = config('systemConfig');
        if (is_string($configArr)) {
            $configArr = Json::decode($configArr);
        }

        //若存在值，则返回
        if (isset($configArr[$name])) {
            return $configArr[$name];
        }

        return null;
    }

    /**
     * 是否是多机构.
     */
    public static function systemManyOrg(): bool
    {
        return ! (defined('SYSTEM_ONE_ORG') && SYSTEM_ONE_ORG);
    }
}
