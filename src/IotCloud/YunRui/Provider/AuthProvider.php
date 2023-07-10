<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\IotCloud\YunRui\Provider;

use Bailing\IotCloud\YunRui\AbstractProvider;

class AuthProvider extends AbstractProvider
{
    /**
     * 获取云睿平台配置.
     * @return mixed
     *               返回值：
     *               androidCode Android安全码
     *               iosCode iOS安全码
     *               realmName 视频播放初始化所需环境
     */
    public function getLechangeConfig()
    {
        return $this->getJson('/gateway/membership/api/common/getLechangeConfig');
    }

    /**
     * 乐橙
     * accessToken：获取管理员token
     * 根据管理员账号appId和appSecret获取accessToken，appId和appSecret可以在控制台-我的应用-应用信息中找到。
     */
    public function getImouAccessToken()
    {
        return $this->postJson('/openapi/accessToken');
    }
}
