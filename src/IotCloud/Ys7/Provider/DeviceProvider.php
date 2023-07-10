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

    class DeviceProvider extends AbstractProvider
    {
        /**
         * 添加设备.
         *
         * @return mixed
         */
        public function addYsDevice(array $params)
        {
            return $this->post('/api/lapp/device/add', $params);
        }

        /**
         * 删除账号下设备（为保证该接口正常使用，请勿在萤石云APP开启终端绑定。
         * 如果该接口报错20031请手机登录萤石云视频客户端“我的”--“通用设置”--“账号安全”--“终端绑定”，关闭即可）.
         *
         * @return mixed
         */
        public function deleteYsDevice(string $deviceSerial)
        {
            $params['deviceSerial'] = $deviceSerial;
            return $this->post('/api/lapp/device/delete', $params);
        }

        /**
         * 修改萤石设备.
         *
         * @return mixed
         */
        public function updateYsDevice(array $params)
        {
            return $this->post('/api/lapp/device/name/update', $params);
        }

        /**
         * 萤石 设备抓拍图片.
         *
         * @return mixed
         */
        public function getYsCapture(array $params)
        {
            return $this->post('/api/lapp/device/capture', $params);
        }

        /**
         * 萤石云 NVR设备关联IPC.
         *
         * @return mixed
         */
        public function addYsDeviceNvrAndIpc(array $params)
        {
            return $this->post('/api/lapp/device/ipc/add', $params);
        }

        /**
         * 萤石云 NVR设备删除IPC.
         *
         * @return mixed
         */
        public function deleteYsDeviceNvrAndIpc(array $params)
        {
            return $this->post('/api/lapp/device/ipc/delete', $params);
        }

        /**
         * 萤石云 该接口用于修改设备视频加密密码（设备重置后修改的密码失效）.
         *
         * @return mixed
         */
        public function updateYsDevicePassword(array $params)
        {
            return $this->post('/api/lapp/device/password/update', $params);
        }

        /**
         * 萤石云 该接口用于生成设备扫描配网二维码二进制数据，需要自行转换成图片（300x300像素大小）。
         *
         * @return mixed
         */
        public function getYsDeviceNetworkQrCode(array $params)
        {
            return $this->post('/api/lapp/device/wifi/qrcode', $params);
        }

        /**
         * 萤石云 修改通道名称.
         *
         * @return mixed
         */
        public function updateYsDeviceChannelName(array $params)
        {
            return $this->post('/api/lapp/camera/name/update', $params);
        }

        /**
         * 萤石云 获取设备列表.
         *
         * @return mixed
         */
        public function getYsDeviceList(array $params = [])
        {
            return $this->post('/api/lapp/device/list', $params);
        }

        /**
         * 萤石云 获取单个设备信息.
         *
         * @return mixed
         */
        public function getYsDeviceInfo(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/device/info', $params);
        }

        /**
         * 萤石云 获取摄像头列表.
         *
         * @return mixed
         */
        public function getYsCameraList(array $params = [])
        {
            return $this->post('/api/lapp/camera/list', $params);
        }

        /**
         * 萤石云 获取设备状态信息.
         *
         * @return mixed
         */
        public function getYsDeviceStatus(array $params)
        {
            return $this->post('/api/lapp/device/status/get', $params);
        }

        /**
         * 萤石云 获取指定设备的通道信息.
         * @param $deviceSerial
         *
         * @return mixed
         */
        public function getYsDeviceChannelInfo($deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/device/camera/list', $params);
        }

        /**
         * 萤石云 根据设备型号以及设备版本号查询设备是否支持萤石协议.
         *
         * @return mixed
         */
        public function checkYsDeviceSupport(array $params)
        {
            return $this->post('/api/lapp/device/support/ezviz', $params);
        }

        /**
         * 萤石云 根据设备序列号查询设备能力集.
         *
         * @return mixed
         */
        public function getYsDeviceCapacity(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/device/capacity', $params);
        }

        /**
         * 萤石云 根据时间获取存储文件信息.
         *
         * @return mixed
         */
        public function getYsDeviceFileInfo(array $params)
        {
            return $this->post('/api/lapp/video/by/time', $params);
        }

        /**
         * 萤石云 本节包含设备开关状态操作的相关接口等。
         */

        /**
         * 获取设备版本信息
         * 查询用户下指定设备的版本信息.
         */
        public function getYsDeviceVersionInfo(string $deviceSerial)
        {
            return $this->post('/api/lapp/device/version/info', ['deviceSerial' => strtoupper($deviceSerial)]);
        }

        /**
         * 设备升级固件(升级设备固件至最新版本).
         *
         * @return mixed
         */
        public function updateYsDeviceUpgrade(string $deviceSerial)
        {
            return $this->post('/api/lapp/device/upgrade', ['deviceSerial' => strtoupper($deviceSerial)]);
        }

        /**
         * 获取设备升级状态(查询用户下指定设备的升级状态，包括升级进度。).
         *
         * @return mixed
         */
        public function updateYsDeviceUpgradeStatus(string $deviceSerial)
        {
            return $this->post('/api/lapp/device/upgrade/status', ['deviceSerial' => strtoupper($deviceSerial)]);
        }
    }
