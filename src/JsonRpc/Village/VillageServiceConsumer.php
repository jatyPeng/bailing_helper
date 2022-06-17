<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\JsonRpc\Village;

use Bailing\Helper\ApiHelper;
use Hyperf\RpcClient\AbstractServiceClient;

class VillageServiceConsumer extends AbstractServiceClient implements VillageServiceInterface
{
    /**
     * 定义对应服务提供者的服务名称.
     * @var string
     */
    protected $serviceName = 'VillageService';

    /**
     * 定义对应服务提供者的服务协议.
     * @var string
     */
    protected $protocol = 'jsonrpc-http';

    /**
     * 添加菜单.
     */
    public function getVillageAndBuild(array $villageIdArr, array $buildArr = [], bool $mergeData = true): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('villageIdArr', 'buildArr', 'mergeData'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 获取单个项目数据.
     */
    public function getVillageById(int $villageId): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('villageId'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 获取多个项目数据.
     */
    public function getVillageByIdArr(array $villageIdArr): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('villageIdArr'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 通过楼栋ID获取楼栋组合好的数据.
     */
    public function getBuildList(array $buildArr = []): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('buildArr'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 填充房间的合同信息.
     * @param int $roomId 房间ID
     * @param array $contractArr 合同列表
     * @param string $leaseEnd 强制设置租赁结束时间
     */
    public function setRoomContract(int $roomId, array $contractArr, string $leaseEnd = ''): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('roomId', 'contractArr'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 获取指定项目或楼栋房间闲置信息.
     * @param mixed $limit
     */
    public function getVacantVillageRoom(array $villageIdArr, array $buildArr = [], array $whereDate = [], $limit = 0): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('villageIdArr', 'buildArr', 'whereDate', 'limit'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 根据指定query获取village数据.
     * @param string
     */
    public function getVillageRoomByQuery(string $query): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('query'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 根据房间ID获取楼层列表数据.
     */
    public function getRooms(array $roomArr = []): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('roomArr'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 根据楼层ID获取楼层列表数据.
     */
    public function getLayers(array $layerArr = []): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('layerArr'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 获取village服务项目租客信息.
     */
    public function getVillageUser(int $user_id, int $owner_id): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('user_id', 'owner_id'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 获取village服务项目相关绑定信息.
     */
    public function getVillageRelevantBuildByQuery(string $village_name, string $build_name, string $layer_name, string $room_name): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('village_name', 'build_name', 'layer_name', 'room_name'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 获取项目在住住户数据，带分页.
     */
    public function getVillageUserByBuildIdArr(int $buildId, int $overdueDay = 0, int $page = 1, int $pageSize = 100): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('buildId', 'overdueDay', 'page', 'pageSize'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }

    /**
     * 设置微服务的参数，用于社区服务判断是否要回调服务.
     */
    public function setVillageMicroServiceConfig(int $villageId, string $name, mixed $value): array
    {
        try {
            return $this->__request(__FUNCTION__, compact('villageId', 'name', 'value'));
        } catch (\Exception $exception) {
            return ApiHelper::genServiceErrorData($this->serviceName, $exception);
        }
    }
}
