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

    class PtzProvider extends AbstractProvider
    {
        /**
         * 开始云台控制
         * 对设备进行开始云台控制，开始云台控制之后必须先调用停止云台控制接口才能进行其他操作，包括其他方向的云台转动.
         *
         * @return mixed
         */
        public function ysPtzStart(array $params)
        {
            return $this->post('/api/lapp/device/ptz/start', $params);
        }

        /**
         * 停止云台控制.
         *
         * @return mixed
         */
        public function ysPtzStop(array $params)
        {
            return $this->post('/api/lapp/device/ptz/stop', $params);
        }

        /**
         * 镜像翻转
         * 对设备进行镜像翻转操作(需要设备支持)。
         *
         * @return mixed
         */
        public function ysPtzMirror(array $params)
        {
            return $this->post('/api/lapp/device/ptz/mirror', $params);
        }

        /**
         * 添加预置点
         * 支持云台控制操作的设备添加预置点.
         *
         * @return mixed
         */
        public function ysDevicePresetAdd(array $params)
        {
            return $this->post('/api/lapp/device/preset/add', $params);
        }

        /**
         * 调用预置点
         * 对预置点进行调用控制.
         *
         * @return mixed
         */
        public function ysDevicePresetMove(array $params)
        {
            return $this->post('/api/lapp/device/preset/move', $params);
        }

        /**
         * 清除预置点.
         *
         * @return mixed
         */
        public function ysDevicePresetClear(array $params)
        {
            return $this->post('/api/lapp/device/preset/clear', $params);
        }
    }
