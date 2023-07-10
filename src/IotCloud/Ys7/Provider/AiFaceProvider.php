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

    class AiFaceProvider extends AbstractProvider
    {
        /**
         * 创建人脸集合.
         *
         * @return mixed
         */
        public function createSet(string $setName)
        {
            $params = ['setName' => $setName];
            return $this->post('/api/lapp/intelligence/face/set/create', $params);
        }

        /**
         * 删除人脸集合.
         * @param string $setTokens 人脸集合的唯一标识，多个以英文逗号分割,一次最多支持 10 个
         *
         * @return mixed
         */
        public function deleteSet(string $setTokens)
        {
            $params = ['setTokens' => $setTokens];
            return $this->post('/api/services/face/set/delete', $params);
        }

        /**
         * 人脸检测.
         *
         * @return mixed
         */
        public function detection(array $params)
        {
            return $this->post('/api/lapp/intelligence/face/analysis/detect', $params);
        }

        /**
         * 人脸注册.
         *
         * @return mixed
         */
        public function register(array $params)
        {
            return $this->post('/api/lapp/intelligence/face/set/register', $params);
        }

        /**
         * 人脸注销
         *
         * @return mixed
         */
        public function remove(array $params)
        {
            return $this->post('/api/lapp/intelligence/face/set/remove', $params);
        }

        /**
         * 人脸比对.
         *
         * @return mixed
         */
        public function compare(array $params)
        {
            return $this->post('/api/lapp/intelligence/face/analysis/compare', $params);
        }

        /**
         * 人脸搜索.
         *
         * @return mixed
         */
        public function search(array $params)
        {
            return $this->post('/api/lapp/intelligence/face/analysis/search', $params);
        }
    }
