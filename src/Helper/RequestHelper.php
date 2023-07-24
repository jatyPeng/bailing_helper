<?php
/**
 * 请求相关.
 */
declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Helper;

use Bailing\Exception\BusinessException;
use Hyperf\Context\ApplicationContext;
use Hyperf\Context\Context;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Redis\RedisFactory;
use Psr\Http\Message\ServerRequestInterface;

class RequestHelper
{
    /**
     * 获取客户端IP.
     */
    public static function getClientIp(?ServerRequestInterface $request = null): string
    {
        if (! $request) {
            $request = Context::get(ServerRequestInterface::class);
        }
        if (! $request) {
            return '';
        }

        $ip = $request->getHeaderLine('x-forwarded-for');
        if (! empty($ip)) {
            return strip_tags($ip);
        }

        $ip = $request->getHeaderLine('x-real-ip');
        if (! empty($ip)) {
            return strip_tags($ip);
        }

        $params = $request->getServerParams();
        return $params['remote_addr'] ?? '';
    }

    /**
     * 获取当前使用协议.
     */
    public static function getClientScheme(?ServerRequestInterface $request = null): string
    {
        if (! $request) {
            $request = Context::get(ServerRequestInterface::class);
        }

        if (! $request) {
            return 'https';
        }

        $scheme = $request->getHeaderLine('x-forwarded-proto');
        if (! empty($scheme)) {
            return $scheme;
        }

        $scheme = $request->getHeaderLine('scheme');
        if (! empty($scheme)) {
            return $scheme;
        }

        return 'https';
    }

    /**
     * 获取当前使用域名.
     */
    public static function getClientDomain(?ServerRequestInterface $request = null): string
    {
        if (! $request) {
            $request = Context::get(ServerRequestInterface::class);
        }
        if (! $request) {
            return cfg('system_domain');
        }
        $host = $request->getHeaderLine('host');
        //如果是IP，则返回后台的域名，微服务请求会是IP
        if (! empty($host) && preg_match('/[a-z]/i', $host)) {
            $port = (string) $request->getHeaderLine('x-port');
            if (empty($port) || in_array($port, ['', '80', '443'])) {
                return $host;
            }
            return $host . ':' . $port;
        }
        return cfg('system_domain');
    }

    public static function isAjax(?ServerRequestInterface $request = null): bool
    {
        if (! $request) {
            $request = Context::get(ServerRequestInterface::class);
        }
        if (! $request) {
            return false;
        }
        return $request->getHeaderLine('x-requested-with') === 'XMLHttpRequest';
    }

    public static function getAdminModule(?ServerRequestInterface $request = null): ?string
    {
        $request || $request = Context::get(ServerRequestInterface::class);
        if (! $request) {
            return null;
        }
        $classAndMethod = self::getClassAndMethod($request);
        if (! $classAndMethod) {
            return null;
        }
        return $classAndMethod[0] . ':' . $classAndMethod[1];
    }

    public static function getClassAndMethod(?ServerRequestInterface $request = null): ?array
    {
        $request || $request = Context::get(ServerRequestInterface::class);
        if (! $request) {
            return null;
        }
        $dispatched = $request->getAttribute(Dispatched::class);
        if ($dispatched instanceof Dispatched) {
            $callback = ($dispatched->handler->callback ?? '');
            if (is_string($callback)) {
                if (str_contains($callback, '@')) {
                    return explode('@', $callback);
                }
                return explode('::', $callback);
            }
            if (is_array($callback) && isset($callback[0], $callback[1])) {
                return $callback;
            }
            return null;
        }
        return null;
    }

    /**
     * 使用“令牌桶”算法实现限流,如果返回False,表示需要限制!
     */
    public static function rateLimit(string $strId, int $intNum, int $intSec, string $strRedisPool = 'rate_limit'): bool
    {
        $strId = trim($strId);
        if (! strlen($strId)) {
            throw new BusinessException(0, '标识不能为空字符');
        }
        if ($intNum <= 0 || $intSec <= 0) {
            throw new BusinessException(0, '限定的数量或时间，必须是大于0的整数');
        }
        $objRedis = ApplicationContext::getContainer()->get(RedisFactory::class)->get($strRedisPool);
        if (! $objRedis) {
            throw new BusinessException(0, '获取Redis连接失败');
        }
        $strKey = 'rate_limit:' . md5($strId);
        $objRedis->watch($strKey);
        $arrData = $objRedis->hGetAll($strKey);
        $arrData || $arrData = [];
        $floN = $arrData['n'] ?? 0;
        $intNow = time();
        $blnRe = true;
        if ($arrData) {
            $intT = $arrData['t'] ?? ($intNow - 1);
            $floN += ($intNum / $intSec) * ($intNow - $intT) - 1;
            $floN = min($intNum, $floN);
            $blnRe = ($floN >= 0);
        } else {
            $floN = $intNum - 1;
        }
        if ($blnRe) {
            $arrData = ['n' => $floN, 't' => $intNow];
            $objRedis->multi();
            $objRedis->hMset($strKey, $arrData);
            $objRedis->expire($strKey, $intSec);
            $arrTemp = $objRedis->exec();
            $blnRe = (is_array($arrTemp) && count($arrTemp));
        } else {
            $objRedis->unwatch();
        }
        return $blnRe;
    }

    /**
     * 取客户唯一ID.
     */
    public static function getGuestUid(?ServerRequestInterface $request = null): ?string
    {
        $uid = null;
        $jwt = JwtHelper::decodeWithRequest(JwtHelper::GUEST_JWT_TOKEN, $request);
        if ($jwt) {
            $uid = JwtHelper::dataToHash($jwt);
        }
        return $uid;
    }
}
