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

interface VillageServiceInterface
{
    /**
     * 获取项目和楼栋组合好的数据.
     */
    public function getVillageAndBuild(array $villageIdArr, array $buildArr = [], bool $mergeData = true): array;

    /**
     * 获取单个项目数据.
     */
    public function getVillageById(int $villageId): array;

    /**
     * 获取多个项目数据.
     */
    public function getVillageByIdArr(array $villageIdArr): array;

    /**
     * 通过楼栋ID获取楼栋组合好的数据.
     */
    public function getBuildList(array $buildArr = []): array;

    /**
     * 填充房间的合同信息.
     */
    public function setRoomContract(int $roomId, array $contractArr): array;

    /**
     * 获取指定项目或楼栋房间闲置信息.
     */
    public function getVacantVillageRoom(array $villageIdArr, array $buildArr = [], array $whereDate = [], int $limit = 0): array;

    /**
     * 根据指定query获取village数据.
     */
    public function getVillageRoomByQuery(string $query): array;

    /**
     * 根据房间ID获取楼层列表数据.
     */
    public function getRooms(array $roomArr = []): array;

    /**
     * 根据楼层ID获取楼层列表数据.
     */
    public function getLayers(array $layerArr = []): array;

    /**
     * 获取village服务项目租客信息.
     */
    public function getVillageUser(int $user_id, int $owner_id): array;

    /**
     * 获取village服务项目相关绑定信息.
     */
    public function getVillageRelevantBuildByQuery(string $village_name, string $build_name, string $layer_name, string $room_name): array;

    /**
     * 获取项目在住住户数据，带分页.
     */
    public function getVillageUserByBuildIdArr(int $buildId, int $overdueDay = 0, int $page = 1, int $pageSize = 100): array;

    /**
     * 设置微服务的参数，用于社区服务判断是否要回调服务.
     */
    public function setVillageMicroServiceConfig(int $villageId, string $name, mixed $value): array;
}
