<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\IotCloud\HikCloud\Provider;

use Bailing\IotCloud\HikCloud\AbstractProvider;

class BuildingProvider extends AbstractProvider
{
    /**
     * 新增楼栋
     * 向社区下添加楼栋。
     *
     * @return mixed
     */
    public function addBuilding(array $params)
    {
        return $this->postJson('/api/v1/estate/system/buildings', $params);
    }

    /**
     * 删除楼栋
     * 删除社区下的楼栋。
     * @param $buildingId
     *
     * @return mixed
     */
    public function deleteBuilding($buildingId)
    {
        $endpoint = '/api/v1/estate/system/buildings/' . $buildingId;
        return $this->deleteJson($endpoint, ['buildingId' => $buildingId]);
    }

    /**
     * 新增单元
     * t向楼栋下添加单元。
     *
     * @return mixed
     */
    public function addUnit(array $params)
    {
        return $this->postJson('/api/v1/estate/system/units', $params);
    }

    /**
     * 删除单元
     * 删除社区下的楼栋。
     * @param $unitId
     *
     * @return mixed
     */
    public function deleteUnit($unitId)
    {
        $endpoint = '/api/v1/estate/system/units/' . $unitId;
        return $this->deleteJson($endpoint, ['unitId' => $unitId]);
    }

    /**
     * 新增户室
     * 向单元下添加户室。
     *
     * @return mixed
     */
    public function addRoom(array $params)
    {
        return $this->postJson('/api/v1/estate/system/rooms', $params);
    }

    /**
     * 删除户室信息。
     * @param $roomId
     *
     * @return mixed
     */
    public function deleteRoom($roomId)
    {
        $endpoint = '/api/v1/estate/system/rooms/' . $roomId;
        return $this->deleteJson($endpoint, ['roomId' => $roomId]);
    }

    /**
     * 根据编号查询户室
     * 根据楼栋编号、单元编号、户室编号查询户室信息。
     *
     * @return mixed
     */
    public function getRoomByNumber(array $params)
    {
        return $this->getJson('/api/v1/estate/system/rooms/actions/info', $params);
    }

    /**
     * 根据户室ID查询社区下的户室信息。（当前仅支持根据关联ID查询）.
     * @param $roomId
     *
     * @return mixed
     */
    public function getRoomById($roomId)
    {
        return $this->getJson('/api/v1/estate/system/rooms/actions/infoById', ['roomId' => $roomId]);
    }

    /**
     *  查询指定社区下的所有房间数据。
     *
     * @return mixed
     */
    public function getRoomList(array $params)
    {
        return $this->postJson('/api/v1/estate/system/rooms/actions/communityRoomList', $params);
    }

    /**
     * 查询指定社区下的所有楼栋数据。.
     *
     * @return mixed
     */
    public function getBuildingList(array $params)
    {
        return $this->postJson('/api/v1/estate/system/buildings/actions/communityBuildingList', $params);
    }

    /**
     * 查询指定楼栋下的所有单元数据。
     *
     * @return mixed
     */
    public function getUnitList(array $params)
    {
        return $this->postJson('/api/v1/estate/system/units/actions/buildingUnitList', $params);
    }

    /**
     * 查询指定楼栋下的所有单元数据。
     *
     * @return mixed
     */
    public function getUnitRoomList(array $params)
    {
        return $this->postJson('/api/v1/estate/system/rooms/actions/unitRoomList', $params);
    }
}
