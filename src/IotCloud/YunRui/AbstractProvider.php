<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\IotCloud\YunRui;

use Bailing\IotCloud\Config;
use Bailing\IotCloud\Kernel\HttpClient;
use Bailing\IotCloud\YunRui\Provider\AccessToken;

abstract class AbstractProvider
{
    use HttpClient;
    use AccessToken;

    public function __construct(protected Application $app, protected Config $config)
    {
    }

    public function get($endpoint, $params = [], $headers = [])
    {
        $header = $this->generateHeader();
        if (! empty($headers)) {
            $header = array_merge($header, $headers);
        }
        return $this->httpClient()->request('GET', $endpoint, [
            'headers' => $header,
            'query' => $params,
        ]);
    }

    public function getJson($endpoint, $params = [], $headers = [])
    {
        $header = $this->generateHeader();
        if (! empty($headers)) {
            $header = array_merge($header, $headers);
        }
        return $this->httpClient()->request('GET', $endpoint, [
            'headers' => $header,
            'json' => $params,
            'query' => $params,
        ]);
    }

    public function postJson($endpoint, $params = [], $headers = [])
    {
        $header = $this->generateHeader();
        if (! empty($headers)) {
            $header = array_merge($header, $headers);
        }
        return $this->httpClient()->request('POST', $endpoint, [
            'headers' => $header,
            'json' => $params,
        ]);
    }

    public function deleteJson($endpoint, $params = [], $headers = [])
    {
        $header = $this->generateHeader();
        if (! empty($headers)) {
            $header = array_merge($header, $headers);
        }
        $options = [
            'form_params' => $params,
            'headers' => $header,
        ];
        return $this->httpClient()->request('DELETE', $endpoint, $options);
    }

    public function post($endpoint, $params = [], $headers = [])
    {
        $header = $this->generateHeader();
        if (! empty($headers)) {
            $header = array_merge($header, $headers);
        }
        return $this->httpClient()->request('POST', $endpoint, [
            'headers' => $header,
            'form_params' => $params,
            'debug' => true,
        ]);
    }

    public function generateHeader($headers = []): array
    {
        $accessToken = $this->getAccessToken();
        $header = [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-type' => 'application/json;charset=utf-8',
            'Accept-Language' => 'zh-CN',
        ];
        if (empty($headers)) {
            return $header;
        }
        return array_merge($header, $headers);
    }

    protected function httpClient()
    {
        return $this->setBaseUri($this->config->getDaHuaYunRuiBaseUri());
    }
}
