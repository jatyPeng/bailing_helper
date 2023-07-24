<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Helper\Annotation;

use Bailing\Annotation\VillageControlMenuPermission;
use Bailing\JsonRpc\Village\VillageRpcServiceInterface;
use Hyperf\Context\ApplicationContext;
use Hyperf\Di\Annotation\AnnotationCollector;

class VillageControlMenuHelper
{
    private string $micro = '';  //  微服务名称

    private array $linkArr = [];   //  module的父和子归集名称

    public function __construct()
    {
        // 会上报微服务名，先删除服务中原有的值再添加
        $this->micro = env('MICRO_NAME', env('APP_NAME'));
    }

    public function build(): void
    {
        $linkData = $this->prepare();
        logger()->info('VillageControlMenuHelper linkData', $linkData);

        $client = ApplicationContext::getContainer()->get(VillageRpcServiceInterface::class);
        $client->call('villageControlMenu', ['linkData' => $linkData]);
    }

    public function prepare(): array
    {
        //得到类方法的所有注解
        $methods = AnnotationCollector::getMethodsByAnnotation(VillageControlMenuPermission::class);
        foreach ($methods as $value) {
            $this->linkArr[] = (array) $value['annotation'];
        }

        stdLog()->debug('VillageControlMenuHelper', [$this->micro, $this->linkArr]);

        return [
            'micro' => $this->micro,
            'linkArr' => $this->linkArr,
        ];
    }
}
