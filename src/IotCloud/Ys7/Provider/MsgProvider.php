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

    class MsgProvider extends AbstractProvider
    {
        /**
         * 获取所有告警信息列表.
         *
         * @return mixed
         */
        public function getYsAlarmList(array $params = [])
        {
            return $this->post('/api/lapp/alarm/list', $params);
        }

        /**
         * 按照设备获取告警消息列表.
         *
         * @return mixed
         */
        public function getYsAlarmDeviceList(array $params)
        {
            return $this->post('/api/lapp/alarm/device/list', $params);
        }
    }
