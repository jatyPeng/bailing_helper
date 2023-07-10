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

class PropertyProvider extends AbstractProvider
{
    /**
     * 新增物业人员信息。
     *
     * @return mixed
     */
    public function addProperty(array $params)
    {
        return $this->postJson('/api/v1/estate/system/property', $params);
    }

    /**
     * 编辑物业人员信息。（全量修改）.
     *
     * @return mixed
     */
    public function updateProperty(array $params)
    {
        return $this->postJson('/api/v1/estate/system/property/actions/updateProperty', $params);
    }

    /**
     * 删除物业人员.
     *
     * @return mixed
     */
    public function deleteProperty(string $personId)
    {
        $endpoint = '/api/v1/estate/system/property/' . $personId;
        return $this->deleteJson($endpoint, ['personId' => $personId]);
    }
}
