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

    class AuthProvider extends AbstractProvider
    {
        public function accessToken()
        {
            $params = [
                'appKey' => $this->config->getYs7Config()['appKey'],
                'appSecret' => $this->config->getYs7Config()['appSecret'],
            ];
            $options = [
                'headers' => [],
                'form_params' => $params,
            ];
            return $this->setBaseUri($this->config->getYsCloudBaseUri())->request('POST', '/api/lapp/token/get', $options);
        }
    }
