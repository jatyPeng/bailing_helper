<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Listener;

use Bailing\Helper\XxlJobTaskHelper;
use GuzzleHttp\Client;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\MainWorkerStart;

/**
 * Hyperf worker 启动后执行.
 * @Listener
 */
class MainWorkerStartListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            MainWorkerStart::class,
        ];
    }

    /**
     * @param MainWorkerStart $event
     */
    public function process(object $event)
    {
        // 初始化打开 xxl-job
        stdLog()->info('xxl-job-task init now');
        if (env('XXL_JOB_ENABLE') === true) {
            stdLog()->info('xxl-job is enable');
            $XxlJobTaskHelper = new XxlJobTaskHelper();
            $XxlJobTaskHelper->build(true);
        }

        // 初始化创建 rabbit-mq vhost
        stdLog()->info('rabbit-mq vhost init now');
        if (env('AMQP_VHOST_AUTO_CREATE') === true && env('AMQP_PORT_ADMIN')) {
            $clientHttp = new Client();
            $response = $clientHttp->request('PUT', sprintf('http://%s:%s/api/vhosts/%s', env('AMQP_HOST'), env('AMQP_PORT_ADMIN'), env('AMQP_VHOST')), [
                'auth' => [env('AMQP_USER'), env('AMQP_PASSWORD')],
                'content-type' => 'application/json',
            ]);
            $mqResultCode = $response->getStatusCode();
            if ($mqResultCode == 201 || $mqResultCode == 204) {
                stdLog()->info('rabbit-mq vhost create ok');
            }
        }
    }
}