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

class FaceDBProvider extends AbstractProvider
{
    /**
     * 超脑人脸库列表.
     *
     * @return mixed
     */
    public function faceList(array $params)
    {
        return $this->getJson('/api/v1/estate/device/faceDatabase/actions/list', $params);
    }

    /**
     * 超脑人脸库添加人脸.
     *
     * @return mixed
     */
    public function ddFace(array $params)
    {
        return $this->postJson('/api/v1/estate/device/faceDatabase/actions/addFace', $params);
    }

    /**
     * 超脑人脸库删除人脸.
     * @param $faceids
     *
     * @return mixed
     */
    public function delFaces($faceids)
    {
        return $this->postJson('/api/v1/estate/device/faceDatabase/actions/delFaces', ['faceIds' => $faceids]);
    }

    /**
     * 同步超脑人脸库.
     *
     * @return mixed
     */
    public function syncFaceDatabase(string $faceDatabaseId)
    {
        return $this->getJson('/api/v1/estate/device/faceDatabase/actions/syncFaceDatabase', ['faceDatabaseId' => $faceDatabaseId]);
    }
}
