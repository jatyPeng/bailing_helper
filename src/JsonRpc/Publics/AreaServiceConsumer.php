<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\JsonRpc\Publics;

use Bailing\Helper\ApiHelper;
use Hyperf\RpcClient\AbstractServiceClient;

class AreaServiceConsumer extends AbstractServiceClient implements AreaServiceInterface
{
    /**
     * 定义对应服务提供者的服务名称.
     * @var string
     */
    protected $serviceName = 'AreaService';

    /**
     * 定义对应服务提供者的服务协议.
     * @var string
     */
    protected $protocol = 'jsonrpc-http';

    /**
     * 获取单个数据.
     */
    public function getArea(int $areaCode): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('areaCode'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }
}
