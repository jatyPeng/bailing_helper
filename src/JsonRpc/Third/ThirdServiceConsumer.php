<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\JsonRpc\Third;

use Bailing\Helper\ApiHelper;
use Hyperf\RpcClient\AbstractServiceClient;

class ThirdServiceConsumer extends AbstractServiceClient implements ThirdServiceInterface
{
    /**
     * 定义对应服务提供者的服务名称.
     * @var string
     */
    protected $serviceName = 'ThirdService';

    /**
     * 定义对应服务提供者的服务协议.
     * @var string
     */
    protected $protocol = 'jsonrpc-http';

    /**
     * 通过指定条件获取第三方授权用户信息.
     */
    public function getThirdUser(array $where): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('where'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 生成微信公众号授权URL.
     */
    public function buildWechatAuthUrl(string $wechatAppid, string $callbackUrl): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('wechatAppid', 'callbackUrl'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 生成支付宝授权URL.
     */
    public function buildAlipayAuthUrl(string $alipayAppid, string $callbackUrl): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('alipayAppid', 'callbackUrl'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 发送微信模板消息给到用户.
     * @param int $orgId 机构ID
     * @param int $userId 用户服务ID
     * @param array $pushData 推送内容
     * @param string $url 链接
     */
    public function pushWechatToSingleByUid(int $orgId, int $userId, array $pushData, string $url): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('orgId', 'userId', 'pushData', 'url'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 获取企业微信联系二维码.
     */
    public function getWorkWechatContactQrcode(int $orgId, array $uidArr, int $scene, bool $isTemp = false): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('orgId', 'uidArr', 'scene', 'isTemp'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }
}
