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
use GuzzleHttp\Exception\GuzzleException;
use Hyperf\Amqp\Annotation\Consumer;
use Hyperf\Amqp\Annotation\Producer;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\MainWorkerStart;
use Swoole\Process;

/**
 * Hyperf worker 启动后执行.
 */
#[Listener]
class MainWorkerStartListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            MainWorkerStart::class,
        ];
    }

    public function process(object $event): void
    {
        // 检测mq的queue、exchange是否以当前服务名开始，避免复制其他代码导致queue相同，引发问题
        if (env('AMQP_USER') && env('AMQP_PASSWORD') && env('APP_NAME')) {
            $consumerExchangeArr = [];
            // Consumer的queue必须以当前服务名开始
            $class = AnnotationCollector::getClassesByAnnotation(Consumer::class);
            foreach ($class as $item) {
                if (! empty($item->queue) && stripos($item->queue, env('APP_NAME')) !== 0) {
                    stdLog()->error('发现mq的queue不符合规则，必须以服务名（' . env('APP_NAME') . '）开始：' . $item->queue);
                    Process::kill((int) file_get_contents(\Hyperf\Config\config('server.settings.pid_file')));
                    break;
                }
                $consumerExchangeArr[] = $item->exchange;
            }

            // Producer的exchange必须要以本服务名开始，特别是当本服务的Consumer存在的时候，避免命令为其他服务。
            $class = AnnotationCollector::getClassesByAnnotation(Producer::class);
            foreach ($class as $item) {
                if (! empty($item->exchange) && stripos($item->exchange, env('APP_NAME')) !== 0 && in_array($item->exchange, $consumerExchangeArr)) {
                    stdLog()->error('发现mq的exchange不符合规则，必须以服务名（' . env('APP_NAME') . '）开始：' . $item->exchange);
                    Process::kill((int) file_get_contents(\Hyperf\Config\config('server.settings.pid_file')));
                    break;
                }
            }
        }

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
            try {
                $response = $clientHttp->request('PUT', sprintf('http://%s:%s/api/vhosts/%s', env('AMQP_HOST'), env('AMQP_PORT_ADMIN'), env('AMQP_BALING_VHOST', 'bailing')), [
                    'auth' => [env('AMQP_USER'), env('AMQP_PASSWORD')],
                    'content-type' => 'application/json',
                ]);

                $mqResultCode = $response->getStatusCode();
                if ($mqResultCode == 201 || $mqResultCode == 204) {
                    stdLog()->info('rabbit-mq vhost create ok');
                }
            } catch (GuzzleException $e) {
                stdLog()->error('rabbit vhost create error：' . $e->getMessage());
            }
        }
    }
}
