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

    class OcrCarProvider extends AbstractProvider
    {
        /**
         * 车辆属性检测.
         *
         * @return mixed
         */
        public function props(array $params)
        {
            return $this->post('/api/lapp/intelligence/vehicle/analysis/props', $params);
        }

        /**
         * 车辆交通属性检测.
         *
         * @return mixed
         */
        public function traffic(array $params)
        {
            return $this->post('/api/lapp/intelligence/vehicle/analysis/props', $params);
        }
    }
