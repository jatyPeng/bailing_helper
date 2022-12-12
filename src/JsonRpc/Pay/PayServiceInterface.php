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

interface PayServiceInterface
{
    /**
     * 下单.
     */
    public function addOrder(array $orderData): array;

    /**
     * 获取订单.
     * @param string $business 业务类型
     * @param int $businessId 业务订单ID
     */
    public function getOrder(string $business, int $businessId): array;

    /**
     * 获取多个订单.
     * @param string $business 业务类型
     * @param array $businessIdArr 业务订单ID
     */
    public function getOrderMore(string $business, array $businessIdArr): array;
}
