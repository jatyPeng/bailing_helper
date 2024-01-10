<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Amqp\Producer;

use Hyperf\Amqp\Annotation\Producer;
use Hyperf\Amqp\Message\ProducerMessage;

/**
 * webhook消息生成器.
 */
#[Producer(exchange: 'log.webhook', routingKey: 'log.webhook')]
class WebHookProducer extends ProducerMessage
{
    public function __construct($data)
    {
        $this->payload = $data;
    }
}
