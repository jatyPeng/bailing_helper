<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\IotCloud\YunRui\Provider;

use Bailing\IotCloud\YunRui\AbstractProvider;

class BuildingProvider extends AbstractProvider
{
    /**
     * 楼栋删除（单元房屋一并删除）
     * 大华云睿平台组织类型有五种，分别是组织、场所、楼栋、单元、房屋，由不同接口创建。
     * 其中场所类型的组织有更多的业务信息，比如地址、负责人信息等；场所即门店、即小区，根据不同产品类型称呼。
     * 其中楼栋、单元、房屋类型的组织是针对大华云睿·社区业务场景而设计的。
     * @param $params
     *
     * @return mixed
     */
    public function deleteBuilding($params)
    {
        return $this->deleteJson('/gateway/membership/api/storeOrg/10', $params);
    }

    /**
     * 新增楼栋单元房屋.
     *
     * @return mixed
     */
    public function addBuilding(array $params)
    {
        return $this->postJson('/gateway/membership/api/storeOrg/buildings', $params);
    }

    /**
     * 查询房屋分页.
     *
     * @return mixed
     */
    public function getBuildingRoomForPage(array $params)
    {
        return $this->getJson('/gateway/membership/api/storeOrg/house/page', $params);
    }

    /**
     * 查询楼栋.
     *
     * @return mixed
     */
    public function getBuildingForPage(array $params)
    {
        return $this->getJson('/gateway/membership/api/storeOrg/building/page', $params);
    }

    /**
     * 查询楼栋、单元、房屋信息.
     *
     * @return mixed
     */
    public function getBuidingUnitRoomList(array $params)
    {
        return $this->postJson('/gateway/membership/api/org/page', $params);
    }

    /**
     * 楼栋修改.
     *
     * @return mixed
     */
    public function updateBuilding(array $params)
    {
        return $this->postJson('/gateway/membership/api/storeOrg/update', $params);
    }

    /**
     * 获取房屋详情.
     * @param $key
     *
     * @return mixed
     */
    public function getBuildingRoomInfo(string $key)
    {
        $params = [
            'key' => $key,
        ];
        return $this->getJson('/gateway/membership/api/storeOrg/house/' . $key, $params);
    }
}
