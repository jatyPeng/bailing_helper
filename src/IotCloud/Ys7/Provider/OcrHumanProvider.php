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

    class OcrHumanProvider extends AbstractProvider
    {
        /**
         * 人体属性识别.
         *
         * @return mixed
         */
        public function bodyProps(array $params)
        {
            return $this->post('/api/lapp/intelligence/vehicle/analysis/props', $params);
        }

        /**
         * 人形检测.
         *
         * @return mixed
         */
        public function detection(array $params)
        {
            return $this->post('/api/lapp/intelligence/human/analysis/detect', $params);
        }
    }
