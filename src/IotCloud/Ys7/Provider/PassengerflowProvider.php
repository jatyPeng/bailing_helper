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

    class PassengerflowProvider extends AbstractProvider
    {
        /**
         * 获取客流统计开关状态
         *
         * @return mixed
         */
        public function getSwitchStatus(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/passengerflow/switch/status', $params);
        }

        /**
         * 设置客流统计开关.
         *
         * @return mixed
         */
        public function setSwitch(array $params)
        {
            return $this->post('/api/lapp/passengerflow/switch/set', $params);
        }

        /**
         * 查询设备某一天的统计客流数据.
         *
         * @return mixed
         */
        public function getDaily(array $params)
        {
            return $this->post('/api/lapp/passengerflow/daily', $params);
        }

        /**
         * 查询设备某一天每小时的客流数据.
         *
         * @return mixed
         */
        public function getHourly(array $params)
        {
            return $this->post('/api/lapp/passengerflow/hourly', $params);
        }

        /**
         * 配置客流统计信息.
         *
         * @return mixed
         */
        public function setConfig(array $params)
        {
            return $this->post('/api/lapp/passengerflow/config', $params);
        }

        /**
         * 获取客流统计配置信息.
         *
         * @return mixed
         */
        public function getConfig(array $params)
        {
            return $this->post('/api/lapp/passengerflow/config/get', $params);
        }
    }
