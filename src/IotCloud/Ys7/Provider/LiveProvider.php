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

    class LiveProvider extends AbstractProvider
    {
        /**
         * 新接口
         * 萤石云 该接口用于通过设备序列号、通道号获取单台设备的播放地址信息，无法获取永久有效期播放地址。
         */
        public function getYsLiveAddr(array $params)
        {
            return $this->post('/api/lapp/v2/live/address/get', $params);
        }

        /**
         * 萤石云 该接口适用于已经开通过直播的用户，用以获取账号下的视频地址列表。
         *
         * @return mixed
         */
        public function getYsUserAllLive(array $params = [])
        {
            return $this->post('/api/lapp/live/video/list', $params);
        }

        /*萤石云 获取指定有效期的直播地址
         * 该接口适用于已经开通过直播的用户，用以根据设备序列号和通道号获取指定有效期的直播地址，可用于防盗链
         * @param array $params
         *
         * @return mixed
         * @author cc
         */
        public function getYsLiveLimited(array $params)
        {
            return $this->post('/api/lapp/live/address/limited', $params);
        }

        /**
         * 萤石云 开通直播功能
         * 该接口用于根据序列号和通道号批量开通直播功能（只支持可观看视频的设备）。
         *
         * @return mixed
         */
        public function openYsLive(string $source)
        {
            $params = ['source' => $source];
            return $this->post('/api/lapp/live/video/open', $params);
        }

        /**
         * 萤石云 获取直播地址
         * 该接口用于根据序列号和通道号批量获取设备的直播地址信息，开通直播后才可获取直播地址 该接口获取直播地址固定不变,永久有效。
         *
         * @return mixed
         */
        public function getYsLiveAddv1(string $source)
        {
            $params = ['source' => $source];
            return $this->post('/api/lapp/live/address/get', $params);
        }

        /**
         * 萤石云 关闭直播功能.
         *
         * @return mixed
         */
        public function closeYsLive(string $source)
        {
            $params = ['source' => $source];
            return $this->post('/api/lapp/live/video/close', $params);
        }

        /**
         * 萤石云 查询账号下流量消耗汇总.
         * @return mixed
         */
        public function getYsLiveTrafficTotal()
        {
            return $this->post('/api/lapp/traffic/user/total');
        }

        /**
         * 萤石云 查询账户下流量消耗详情.
         */
        public function getYsTrafficDetail(array $params)
        {
            return $this->post('/api/lapp/traffic/user/detail', $params);
        }

        /**
         * 萤石云 查询账户下某天流量消耗详情.
         *
         * @return mixed
         */
        public function getYsTrafficDayDetail(array $params)
        {
            return $this->post('/api/lapp/traffic/day/detail', $params);
        }

        /**
         * 萤石云 查询指定设备在某一时间段消耗流量数据.
         *
         * @return mixed
         */
        public function getYsTrafficDeviceDetail(array $params)
        {
            return $this->post('/api/lapp/traffic/device/detail', $params);
        }
    }
