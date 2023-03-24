<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\JsonRpc\Pay;

use Bailing\Helper\ApiHelper;
use Hyperf\RpcClient\AbstractServiceClient;

class PayServiceConsumer extends AbstractServiceClient implements PayServiceInterface
{
    /**
     * 定义对应服务提供者的服务名称.
     */
    protected string $serviceName = 'PayService';

    /**
     * 定义对应服务提供者的服务协议.
     */
    protected string $protocol = 'jsonrpc-http';

    /**
     * 下单.
     */
    public function addOrder(array $orderData): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('orderData'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 获取订单.
     * @param string $business 业务类型
     * @param int $businessId 业务订单ID
     */
    public function getOrder(string $business, int $businessId): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('business', 'businessId'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 获取多个订单.
     * @param string $business 业务类型
     * @param array $businessIdArr 业务订单ID
     */
    public function getOrderMore(string $business, array $businessIdArr): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('business', 'businessIdArr'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 修改订单价格.
     * @param string $orderId 支付业务订单号
     * @param float $orderMoney 新的订单金额
     */
    public function changeOrderMoney(string $orderId, float $orderMoney): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('orderId', 'orderMoney'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 订单退款.
     * @param string $orderId 支付业务订单号
     * @param float $orderMoney 退款订单金额
     * @param string $remark 备注
     */
    public function refundOrderMoney(string $orderId, float $refundMoney, string $remark): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('orderId', 'refundMoney', 'remark'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }
}
