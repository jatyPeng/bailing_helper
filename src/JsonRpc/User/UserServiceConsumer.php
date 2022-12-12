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

use Bailing\Helper\ApiHelper;
use Hyperf\RpcClient\AbstractServiceClient;

class UserServiceConsumer extends AbstractServiceClient implements UserServiceInterface
{
    /**
     * 定义对应服务提供者的服务名称.
     * @var string
     */
    protected $serviceName = 'UserService';

    /**
     * 定义对应服务提供者的服务协议.
     * @var string
     */
    protected $protocol = 'jsonrpc-http';

    /**
     * 通过手机号找用户.
     */
    public function getUserByPhone(string $phone, int $phoneCountry = 86): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('phone', 'phoneCountry'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 通过uid集合找用户.
     */
    public function getUserByUids(array $uidArr): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('uidArr'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 通过uid获取user token.
     */
    public function getUserTokenByUid(int $uid): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('uid'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 通过uid和appid找对应的用户第三方授权信息.
     */
    public function getUserThirdByUidAndAppid(int $uid, string $appid): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('uid', 'appid'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 通过uid数组和appid找对应的用户第三方授权信息.
     */
    public function getUserThirdByUidArrAndAppid(array $uidArr, string $appid): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('uidArr', 'appid'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 根据指定uid获取用户附属信息.
     */
    public function getUserExtraData(int $uid): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('uid'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 查询用户第三方信息.
     */
    public function getUserThird(string $appid, int $thirdPlatform, string $thirdToken): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('appid', 'thirdPlatform', 'thirdToken'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 绑定用户第三方信息.
     */
    public function addUserThird(int $user_id, string|null $appid, array $userInfo): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('user_id', 'appid', 'userInfo'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 通过uid集合和appid找用户的app设备号.
     */
    public function getAppDevice(array $uidArr, string $appid): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('uidArr', 'appid'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }
}
