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

    class OcrTextProvider extends AbstractProvider
    {
        /**
         * 通用文字识别.
         *
         * @return mixed
         */
        public function img2Text(array $params)
        {
            return $this->post('/api/lapp/intelligence/ocr/generic', $params);
        }

        /**
         * 银行卡识别.
         */
        public function bankCard(array $params)
        {
            $this->post('/api/lapp/intelligence/ocr/bankCard', $params);
        }

        /**
         * 身份证识别.
         *
         * @return mixed
         */
        public function idCard(array $params)
        {
            return $this->post('/api/lapp/intelligence/ocr/idCard', $params);
        }

        /**
         * 驾驶证识别.
         *
         * @return mixed
         */
        public function driverLicense(array $params)
        {
            return $this->post('/api/lapp/intelligence/ocr/driverLicense', $params);
        }

        /**
         * 行驶证识别.
         *
         * @return mixed
         */
        public function vehicleLicense(array $params)
        {
            return $this->post('/api/lapp/intelligence/ocr/vehicleLicens', $params);
        }

        /**
         * 营业执照识别.
         *
         * @return mixed
         */
        public function businessLicense(array $params)
        {
            return $this->post('/api/lapp/intelligence/ocr/businessLicense', $params);
        }

        /**
         * 通用票据识别.
         *
         * @return mixed
         */
        public function receipt(array $params)
        {
            return $this->post('/api/lapp/intelligence/ocr/receipt', $params);
        }

        /**
         * 车牌识别.
         *
         * @return mixed
         */
        public function licensePlate(array $params)
        {
            return $this->post('/api/lapp/intelligence/ocr/licensePlate', $params);
        }
    }
