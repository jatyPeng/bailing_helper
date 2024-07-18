<?php

namespace Bailing\Aspect;
use Bailing\Annotation\RateRequest;
use Bailing\Helper\ApiHelper;
use Bailing\Helper\RequestHelper;
use Hyperf\Codec\Json;
use Hyperf\Context\Context;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;

class RateRequestAspect extends AbstractAspect
{
    public array $classes = [];

    public array $annotations = [
        RateRequest::class,
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $metadata = $proceedingJoinPoint->getAnnotationMetadata();

        // 获取限流时间
        $waitTimeout = 6;
        $rateKey = '';
        foreach($metadata->method as $key => $annotation) {
            if($annotation instanceof RateRequest){
                $waitTimeout = $annotation->waitTimeout;
                $rateKey = $annotation->rateKey;
                break;
            }
        }


        $classMethod = explode(':', RequestHelper::getAdminModule());

        // 如果 ratekey为空，
        if(empty($rateKey)){
            $handleArr = request()->all();
        } else {
            $keyArr = explode(',', $rateKey);
            foreach($keyArr as $item){
                $handleArr[$item] = request()->input($item, '') ?: $item;
            }
        }


        //如果为空，认定为raw请求
        if (empty($handleArr)) {
            $handleArr = [
                'body' => request()->getBody()->getContents(),
            ];
        }

        $handleArr['class'] = $classMethod[0];
        $handleArr['method'] = $classMethod[1];

        $redis = redis();
        $strKey = 'rate_request:' . md5(serialize($handleArr));
        $result = $redis->set($strKey, Json::encode($handleArr), ['NX', 'EX' => $waitTimeout]);
        if (empty($result)) {
            stdLog()->warning('RateRequestAspect', $handleArr);
            return self::json('该相关请求正在执行，请稍后再试');
        }

        $result = $proceedingJoinPoint->process();

        //删redis缓存
        $redis->del($strKey);

        return $result;
    }

    private static function json(string $msg, int $errCode = ApiHelper::NORMAL_ERROR)
    {
        $body = new SwooleStream(Json::encode(ApiHelper::genErrorData($msg, $errCode)));
        return Context::get(ResponseInterface::class)
            ->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withBody($body);
    }
}