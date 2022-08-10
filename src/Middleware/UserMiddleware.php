<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Middleware;

use Bailing\Helper\ApiHelper;
use Bailing\Helper\JwtHelper;
use Bailing\Helper\RequestHelper;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Codec\Json;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserMiddleware implements MiddlewareInterface
{
    /*
     * User格式：{"id":6,"phone_country":86,"phone":"17755185540","last_time":"2022-03-20 11:01:58","last_ip":"127.0.0.1","sharer":[],"tokenType":"user"}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $classMethod = explode(':', RequestHelper::getAdminModule());
        $annotations = AnnotationCollector::getClassMethodAnnotation($classMethod[0], $classMethod[1]);
        $jwtData = JwtHelper::decodeWithRequest('USER', $request);
        if (! $jwtData && $annotationsMiddleware = (array) $annotations['Hyperf\HttpServer\Annotation\Middleware']) {
            if (! empty($annotationsMiddleware)) {
                $annotationsMiddleware = array_values($annotationsMiddleware);
                array_walk($annotationsMiddleware, function (&$val, $key) {$val = array_unique(array_column((array) $val, 'middleware')); });
            }
            //放行中间件配置项
            $passOtherMiddleware = ['Bailing\Middleware\OrgMiddleware', 'Bailing\Middleware\SystemMiddleware'];
            $passAuth = array_intersect($annotationsMiddleware[0], $passOtherMiddleware);
            if (! empty($passAuth)) {
                return $handler->handle($request);
            }
            //针对单个接口继承多个服务中间件鉴权 则只校验本服务token-type的token 其他服务则放行
        }
        // 如果其他中间件有存储用户信息到上下文，则证明其他中间件有校验用户身份，直接放行。若需要两个中间件，user中间件放后面。
        if (! $jwtData && contextGet('nowUser')) {
            return $handler->handle($request);
        }

        // 未登录，或登录状态超过14天
        if (! $jwtData || time() - $jwtData->iat > 86400 * 14) {
            return self::json('请登录！');
        }

        $jwtData->data->tokenType = 'user';

        //将登录信息存储到协程上下文
        contextSet('nowUser', $jwtData->data);
        return $handler->handle($request);
    }

    private static function json(string $msg, int $errCode = ApiHelper::LOGIN_ERROR)
    {
        $body = new SwooleStream(Json::encode(ApiHelper::genErrorData($msg, $errCode)));
        return Context::get(ResponseInterface::class)
            ->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withBody($body);
    }
}
