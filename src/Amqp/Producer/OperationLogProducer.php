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
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Codec\Json;

#[Producer(exchange: 'operation_log', routingKey: 'operation_log')]
class OperationLogProducer extends ProducerMessage
{
    #[Inject]
    private ConfigInterface $config;

    public function __construct($data)
    {
        $logConfig = config('logConfig');
        if (is_string($logConfig)) {
            $logConfig = Json::decode($logConfig);
        }

        $this->config->set('amqp.log.host', env('LOG_AMQP_HOST', $logConfig['amqp']['AMQP_HOST']));
        $this->config->set('amqp.log.port', (int) $logConfig['amqp']['AMQP_PORT']);
        $this->config->set('amqp.log.user', $logConfig['amqp']['AMQP_USER']);
        $this->config->set('amqp.log.password', $logConfig['amqp']['AMQP_PASSWORD']);
        $this->config->set('amqp.log.vhost', $logConfig['amqp']['AMQP_VHOST']);

        // 设置不同 pool
        $this->poolName = 'log';

        $this->payload = $data;
    }
}
