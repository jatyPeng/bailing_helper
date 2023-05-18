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
use Bailing\JsonRpc\Org\OrgServiceInterface;
use Hyperf\Context\Context;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use Hyperf\Utils\Codec\Json;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class OrgMiddleware implements MiddlewareInterface
{
    private const SUPER_ROLE_LEVEL = 99; //机构创建者

    protected ContainerInterface $container;

    protected RequestInterface $request;

    protected HttpResponse $response;

    public function __construct(ContainerInterface $container, HttpResponse $response, RequestInterface $request)
    {
        $this->container = $container;
        $this->response = $response;
        $this->request = $request;
    }

    /**
     * Org格式：{"id":1,"phone_country":86,"phone":"1xxxxxxxxxx","last_time":"2023-02-23 14:15:10","last_ip":"127.0.0.1","user_id":1,"name":"管理员","org_id":1,"org_name":"xx楼宇","role_id":1,"level":99,"isSuper":true,"isLayerAdmin":false}
     * Ps：id为user服务id(user服务-user表-id) 用户唯一标识身份id； user_id为所处org用户身份id(org服务-user表-id).
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $classMethod = explode(':', RequestHelper::getAdminModule());
        $annotations = AnnotationCollector::getClassMethodAnnotation($classMethod[0], $classMethod[1]);
        $jwtData = JwtHelper::decodeWithRequest('ORG', $request);
        $annotationsArr = (array) $annotations;
        if (! $jwtData && isset($annotationsArr['Hyperf\HttpServer\Annotation\Middleware']) && $annotationsMiddleware = (array) $annotationsArr['Hyperf\HttpServer\Annotation\Middleware']) {
            if (! empty($annotationsMiddleware)) {
                $annotationsMiddleware = array_values($annotationsMiddleware);
                array_walk($annotationsMiddleware, function (&$val, $key) {$val = array_unique(array_column((array) $val, 'middleware')); });
            }
            //放行中间件配置项
            $passOtherMiddleware = ['Bailing\Middleware\UserMiddleware', 'Bailing\Middleware\SystemMiddleware'];
            $passAuth = array_intersect($annotationsMiddleware[0], $passOtherMiddleware);
            if (! empty($passAuth)) {
                return $handler->handle($request);
            }
            //针对单个接口继承多个服务中间件鉴权 则只校验本服务token-type的token 其他服务则放行
        }
        if (! $jwtData || time() - $jwtData->iat > 86400 * 14) { // 未登录，或登录状态超过14天
            return self::json('请登录！');
        }
        try {
            $redisUserClient = redis('user');
            if ($redisUserClient->exists('user_status_' . $jwtData->data->id) && $redisUserClient->get('user_status_' . $jwtData->data->id) == 'deleted') {
                return self::json('用户信息不存在,请重新注册登录！');
            }
        } catch (\Exception $exception) {
            logger()->error('USER REDIS CLIENT ERROR', ['module' => RequestHelper::getAdminModule()]);
        }
        try {
            $redisOrgClient = redis('org');
            if ($redisOrgClient->exists('org_user_status_' . $jwtData->data->id) && $redisOrgClient->get('org_user_status_' . $jwtData->data->id) == 'deleted') {
                return self::json('您已被移出该机构!');
            }
        } catch (\Exception $exception) {
            logger()->error('ORG REDIS CLIENT ERROR', ['module' => RequestHelper::getAdminModule()]);
        }

        $jwtData->data->tokenType = 'org';

        if (isset($jwtData->data->level) && $jwtData->data->level == self::SUPER_ROLE_LEVEL) { // 机构创建者=超级管理员 拥有最高访问权限
            contextSet('nowUser', $jwtData->data); //将登录信息存储到协程上下文
            unset($jwtData);
            return $handler->handle($request);
        }
        //放行不配置org权限菜单注解的路由
        if (! array_key_exists('Bailing\Annotation\OrgPermission', $annotations)) {
            contextSet('nowUser', $jwtData->data); //将登录信息存储到协程上下文
            unset($jwtData);
            return $handler->handle($request);
        }

        $adminRole = $jwtData->data->role_id ?? 0;
        if ($jwtData->data->id && empty($adminRole)) {
            return self::json('账号异常!未绑定角色身份', ApiHelper::AUTH_ERROR);
        }
        if (! $adminRole || ! $this->allowAccess($jwtData->data->role_id)) {
            return self::json('无权访问', ApiHelper::AUTH_ERROR);
        }
        contextSet('nowUser', $jwtData->data); //将登录信息存储到协程上下文
        unset($jwtData, $adminRole);
        return $handler->handle($request);
    }

    private static function json(string $msg, int $errCode = ApiHelper::LOGIN_ERROR)
    {
        $body = new SwooleStream(Json::encode(ApiHelper::genErrorData($msg, $errCode)));
        return Context::get(ResponseInterface::class)
            ->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withBody($body);
    }

    /**
     * Notes:判断路由节点访问是否拥有权限(机构后台控制)
     * User: Endness
     * Date: 2021/10/11
     * Time: 17:22.
     * @param int $role_id 当前用户角色id
     */
    private function allowAccess(int $role_id)
    {
        if (env('APP_NAME') == 'org' && class_exists('\App\JsonRpc\OrgService')) {
            $orgService = container()->get(\App\JsonRpc\OrgService::class)->getRoleRbacList($role_id);
        } else {
            $orgService = container()->get(OrgServiceInterface::class)->getRoleRbacList($role_id);
        }
        $adminModule = RequestHelper::getAdminModule();
        $rbacAccess = ! empty($orgService['data']['rbacList']) ? $orgService['data']['rbacList'] : [];
        if (in_array($adminModule, $rbacAccess) && ! empty($rbacAccess)) {
            return true;
        }
        return false;
    }
}
