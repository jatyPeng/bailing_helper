<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Aspect;

use GuzzleHttp\Client;
use Hyperf\Contract\TranslatorInterface;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;

/**
 * 拦截jsonRpc，增加语言参数.
 *
 * @deprecated Client::getConfig will be removed in guzzlehttp/guzzle:8.0.
 */
#[Aspect]
class JsonRpcHttpTransporterGetClientAspect extends AbstractAspect
{
    // 要切入的类或 Trait，可以多个，亦可通过 :: 标识到具体的某个方法，通过 * 可以模糊匹配
    public array $classes = [
        'Hyperf\JsonRpc\JsonRpcHttpTransporter::getClient',
    ];

    // 要切入的注解，具体切入的还是使用了这些注解的类，仅可切入类注解和类方法注解
    public array $annotations = [];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $result = $proceedingJoinPoint->process();
        $config = $result->getConfig();
        $config['headers']['Language'] = container()->get(TranslatorInterface::class)->getLocale();

        return new Client($config);
    }
}
