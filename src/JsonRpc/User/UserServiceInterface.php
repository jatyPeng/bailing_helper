<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\JsonRpc\User;

interface UserServiceInterface
{
    /**
     * 通过手机号找用户.
     */
    public function getUserByPhone(string $phone, int $phoneCountry = 86): array;

    /**
     * 通过uid集合找用户.
     */
    public function getUserByUids(array $uidArr): array;

    /**
     * 通过uid获取user token.
     */
    public function getUserTokenByUid(int $uid): array;

    /**
     * 通过uid和appid找对应的用户第三方授权信息.
     */
    public function getUserThirdByUidAndAppid(int $uid, string $appid): array;

    /**
     * 通过uid数组和appid找对应的用户第三方授权信息.
     */
    public function getUserThirdByUidArrAndAppid(array $uidArr, string $appid): array;

    /**
     * 根据指定uid获取用户附属信息.
     */
    public function getUserExtraData(int $uid): array;

    /**
     * 查询用户第三方信息.
     */
    public function getUserThird(string $appid, int $thirdPlatform, string $thirdToken): array;

    /**
     * 绑定用户第三方信息.
     */
    public function addUserThird(int $user_id, string|null $appid, array $userInfo): array;

    /**
     * 通过uid集合和appid找用户的app设备号.
     */
    public function getAppDevice(array $uidArr, string $appid): array;
}
