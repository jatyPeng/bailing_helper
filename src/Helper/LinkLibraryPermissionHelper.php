<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Helper;

use Bailing\Annotation\LinkLibraryPermission;
use Bailing\JsonRpc\Publics\LinkLibraryServiceInterface;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\Utils\ApplicationContext;

class LinkLibraryPermissionHelper
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
        $client = ApplicationContext::getContainer()->get(LinkLibraryServiceInterface::class);
        logger()->info('linkLibrary linkData', $linkData);
        $client->addLink($linkData);
    }

    public function prepare(): array
    {
        //得到类方法的所有注解
        $methods = AnnotationCollector::getMethodsByAnnotation(LinkLibraryPermission::class);
        foreach ($methods as $value) {
            $this->linkArr[] = (array) $value['annotation'];
        }

        return [
            'micro' => $this->micro,
            'linkArr' => $this->linkArr,
        ];
    }
}
