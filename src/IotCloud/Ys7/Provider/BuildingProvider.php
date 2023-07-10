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

    class BuildingProvider extends AbstractProvider
    {
        /**
         * 获取通话状态
         *
         * @return mixed
         */
        public function getCallStatus(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/building/device/call/status', $params);
        }

        /**
         * 远程开锁
         *
         * @return mixed
         */
        public function unlock(array $params)
        {
            return $this->post('/api/lapp/building/device/unlock', $params);
        }

        /**
         * 通话操作.
         *
         * @return mixed
         */
        public function doCall(array $params)
        {
            return $this->post('/api/lapp/building/device/call', $params);
        }

        /**
         * 获取主叫信息.
         *
         * @return mixed
         */
        public function getDialing(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/building/device/dialing/get', $params);
        }

        /**
         * 获取门口机列表.
         *
         * @return mixed
         */
        public function getDoorDeviceList(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/building/device/list', $params);
        }

        /**
         * 配置麦克风扬声器参数.
         *
         * @return mixed
         */
        public function setAudioConfig(array $params)
        {
            return $this->post('/api/lapp/building/device/audio/config', $params);
        }

        /**
         * 获取麦克风扬声器参数.
         *
         * @return mixed
         */
        public function getAudioConfig(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/building/device/audio/config/get', $params);
        }

        /**
         * 配置移动侦测开关和灵敏度.
         *
         * @return mixed
         */
        public function setDefenceConfig(array $params)
        {
            return $this->post('/api/lapp/building/device/defence/config', $params);
        }

        /**
         * 获取移动侦测开关状态和灵敏度.
         *
         * @return mixed
         */
        public function getDefenceConfig(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/building/device/defence/config/get', $params);
        }

        /**
         * 获取TF卡状态、总容量、剩余容量.
         *
         * @return mixed
         */
        public function getTfCardInfo(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/building/device/storage/status', $params);
        }

        /**
         * TF卡格式化.
         *
         * @return mixed
         */
        public function formatTfCard(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/building/device/storage/format', $params);
        }

        /**
         * APP登录设备处理.
         *
         * @return mixed
         */
        public function getAppLoginDeviceInfo(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/building/device/info/login', $params);
        }

        /**
         * 获取智能锁列表.
         *
         * @return mixed
         */
        public function getSmartLockList(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/building/device/smartlock/list', $params);
        }

        /**
         * 智能锁开锁
         *
         * @return mixed
         */
        public function openSmartLock(array $params)
        {
            return $this->post('/api/lapp/building/device/smartlock/unlock', $params);
        }
    }
