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

    class AccountProvider extends AbstractProvider
    {
        /**
         * 创建子账户.
         *
         * @return mixed
         */
        public function create(array $params)
        {
            return $this->post('/api/lapp/ram/account/create', $params);
        }

        /**
         * 获取单个子账户信息.
         *
         * @return mixed
         */
        public function getUser(array $params)
        {
            return $this->post('/api/lapp/ram/account/get', $params);
        }

        /**
         * 获取子账户信息列表.
         *
         * @return mixed
         */
        public function getUserList(array $params = [])
        {
            return $this->post('/api/lapp/ram/account/get', $params);
        }

        /**
         * 修改当前子账户密码
         *
         * @return mixed
         */
        public function updatePassword(array $params)
        {
            return $this->post('/api/lapp/ram/account/updatePassword', $params);
        }

        /**
         * 设置子账户的授权策略.
         *
         * @return mixed
         */
        public function setPolicy(array $params)
        {
            return $this->post('/api/lapp/ram/policy/set', $params);
        }

        /**
         * 增加子账户权限.
         *
         * @return mixed
         */
        public function addStatement(array $params)
        {
            return $this->post('/api/lapp/ram/statement/add', $params);
        }

        /**
         * 删除子账户权限.
         */
        public function deleteAccount(array $params)
        {
            $this->post('/api/lapp/ram/statement/delete', $params);
        }

        /**
         * 获取B模式子账户accessToken.
         *
         * @return mixed
         */
        public function getBModelAccount(string $accountId)
        {
            $params = ['accountId' => $accountId];
            return $this->post('/api/lapp/ram/token/get', $params);
        }

        /**
         * 删除子账户.
         *
         * @return mixed
         */
        public function deleteSubAccount(string $accountId)
        {
            $params = ['accountId' => $accountId];
            return $this->post('/api/lapp/ram/account/delete', $params);
        }
    }
