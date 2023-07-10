<?php

    declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\IotCloud\Ys7\Provider;

    use Bailing\IotCloud\Ys7\AbstractProvider;

    class DetectorProvider extends AbstractProvider
    {
        /**
         * 获取探测器列表.
         *
         * @return mixed
         */
        public function getDetectorList(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/detector/list', $params);
        }

        /**
         * 设置探测器状态
         *
         * @return mixed
         */
        public function setDetetorStatus(array $params)
        {
            return $this->post('/api/lapp/detector/status/set', $params);
        }

        /**
         * 删除探测器.
         *
         * @return mixed
         */
        public function deleteDetetor(array $params)
        {
            return $this->post('/api/lapp/detector/delete', $params);
        }

        /**
         * 获取可关联的IPC列表.
         *
         * @return mixed
         */
        public function getDetectorCanBindIpc(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/detector/ipc/list/bindable', $params);
        }

        /**
         * 获取已关联的IPC列表.
         *
         * @return mixed
         */
        public function getDetectorAndIpcBindable(array $params)
        {
            return $this->post('/api/lapp/detector/ipc/list/bind', $params);
        }

        /**
         * 设置探测器与IPC的关联关系.
         *
         * @return mixed
         */
        public function setIpcRelationDetector(array $params)
        {
            return $this->post('/api/lapp/detector/ipc/relation/set', $params);
        }

        /**
         * 修改探测器名称.
         *
         * @return mixed
         */
        public function updateDetectorName(array $params)
        {
            return $this->post('/api/lapp/detector/name/change', $params);
        }

        /**
         * 设备一键消警.
         *
         * @return mixed
         */
        public function cancelAlarm(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/detector/cancelAlarm', $params);
        }
    }
