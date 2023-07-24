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

use RedisException;

class RateRequestHelper
{
    /**
     * 手动删除访问令牌的缓存.
     */
    public static function clear(): bool
    {
        $redis = redis();
        try {
            $rateRequestKey = contextGet('rateRequestKey');
            if ($rateRequestKey) {
                $redis->del($rateRequestKey);
            }
        } catch (RedisException $e) {
            stdLog()->error('RateRequestHelper redis del：' . $e->getMessage());
            return false;
        }
        return true;
    }
}
