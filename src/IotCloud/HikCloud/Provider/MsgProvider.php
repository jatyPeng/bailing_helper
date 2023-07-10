<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\IotCloud\HikCloud\Provider;

use Bailing\IotCloud\HikCloud\AbstractProvider;

class MsgProvider extends AbstractProvider
{
    /**
     * 创建消费者
     * 1.该接口用于创建消费者ID，最多同时存在五个消费者ID。
     * 2.消费者如果5分钟未调用拉取消息接口将被删除。
     * @return mixed
     */
    public function createCustomer()
    {
        $params = ['consumerName' => 'group1'];
        return $this->post('/api/v1/mq/consumer/group1', $params);
    }

    /**
     * 消费消息.
     *
     * @return mixed
     */
    public function getCustomer(array $params)
    {
        return $this->post('/api/v1/mq/consumer/messages', $params);
    }

    /**
     * 提交偏移量
     * 用于手动提交偏移量，提交上次消费到的消息的偏移量。
     * 消费消息和提交偏移量必须使用同一个consumerId，若不使用同一个consumerId进行提交，则会提交失败，消息可能会被重复消费。
     * @param $consumerId
     *
     * @return mixed
     */
    public function offsetCustomer($consumerId)
    {
        return $this->post('/api/v1/mq/consumer/offsets', ['consumerId' => $consumerId]);
    }
}
