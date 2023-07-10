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

    class HubProvider extends AbstractProvider
    {
        /**
         * 获取子设备列表.
         *
         * @return mixed
         */
        public function getHubDeviceList(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/hub/device/sub/list', $params);
        }

        /**
         * 获取子设备信息.
         *
         * @return mixed
         */
        public function getHubDeviceSubList(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/hub/device/sub/info', $params);
        }

        /**
         * 获取子设备通道信息.
         *
         * @return mixed
         */
        public function getHubDeviceCameraInfo(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/hub/device/camera/list', $params);
        }
    }
