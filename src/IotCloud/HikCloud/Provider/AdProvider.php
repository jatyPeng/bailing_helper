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

class AdProvider extends AbstractProvider
{
    /**
     * 下发广告.
     *
     * @return mixed
     */
    public function publishProgram(array $params)
    {
        return $this->postJson('/api/v1/estate/publish/actions/publishProgram', $params);
    }

    /**
     * 删除广告.
     * @param $deviceIds
     *
     * @return mixed
     */
    public function deleteProgram($deviceIds)
    {
        return $this->postJson('/api/v1/estate/publish/actions/deleteProgram', ['deviceIds' => $deviceIds]);
    }
}
