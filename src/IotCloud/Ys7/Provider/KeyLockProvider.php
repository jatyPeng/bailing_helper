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

    class KeyLockProvider extends AbstractProvider
    {
        /**
         * 启动指纹锁验证
         *
         * @return mixed
         */
        public function verifyKeyLock(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/keylock/local/verify', $params);
        }

        /**
         * 获取指纹锁用户列表.
         *
         * @return mixed
         */
        public function getKeyLockUserList(string $deviceSerial)
        {
            $params = ['deviceSerial' => $deviceSerial];
            return $this->post('/api/lapp/keylock/user/list', $params);
        }

        /**
         * 分页获取开门记录.
         *
         * @return mixed
         */
        public function getKeyLockOpenRecord(array $params)
        {
            return $this->post('/api/lapp/keylock/open/list', $params);
        }
    }
