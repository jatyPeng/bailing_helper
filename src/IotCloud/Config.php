<?php

    declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\IotCloud;

class Config
{
    protected array $appConfig;

    protected array $guzzleConfig = [
        'headers' => [
            'charset' => 'UTF-8',
        ],
        'http_errors' => false,
    ];

    public function __construct(array $config)
    {
        $this->appConfig = $config;
    }

    public function getAppConfig()
    {
        return $this->appConfig;
    }

    public function getHikConfig(): array
    {
        if (isset($this->getAppConfig()['hikcloud'])) {
            return $this->getAppConfig()['hikcloud'];
        }
        return $this->getAppConfig();
    }

    public function getYunRuiConfig(): array
    {
        if (isset($this->getAppConfig()['yunrui'])) {
            return $this->getAppConfig()['yunrui'];
        }
        return $this->getAppConfig();
    }

    public function getYs7Config(): array
    {
        if (isset($this->getAppConfig()['ys7'])) {
            return $this->getAppConfig()['ys7'];
        }
        return $this->getAppConfig();
    }

    public function getImouConfig(): array
    {
        if (isset($this->getAppConfig()['imou'])) {
            return $this->getAppConfig()['imou'];
        }
        return $this->getAppConfig();
    }

    /**
     * 海康云眸.
     */
    public function getHikCloudBaseUri(): string
    {
        return 'https://api2.hik-cloud.com';
    }

    /**
     * 萤石云.
     */
    public function getYsCloudBaseUri(): string
    {
        return 'https://open.ys7.com';
    }

    /**
     * 大华云睿
     */
    public function getDaHuaYunRuiBaseUri(): string
    {
        return 'https://www.cloud-dahua.com';
    }

    /**
     * 乐橙.
     */
    public function getLeChengeBaseUri(): string
    {
        return 'https://openapi.lechange.cn';
    }

    public function getGuzzleConfig(): array
    {
        return $this->guzzleConfig;
    }
}
