<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Helper\Webhook;

use GuzzleHttp\Client;
use Hyperf\Codec\Json;

class FeishuHelper
{
    public static function checkConfig(): bool
    {
        $url = cfg('dev_feishu_robot_url');
        $secret = cfg('dev_feishu_robot_secret');
        if ($url && $secret) {
            return true;
        }
        return false;
    }

    public static function sendMarkDown($title, $content): bool
    {
        $url = cfg('dev_feishu_robot_url');
        $secret = cfg('dev_feishu_robot_secret');

        $timestamp = time();
        $data = [
            'timestamp' => $timestamp,
            'sign' => self::getSign($timestamp, $secret),
            'msg_type' => 'post',
            'content' => [
                'post' => [
                    'zh_cn' => [
                        'title' => $title,
                        'content' => $content,
                    ],
                ],
            ],
        ];

        $clientHttp = new Client();
        $response = $clientHttp->post($url, [
            'body' => Json::encode($data),
            'headers' => ['content-type' => 'application/json'],
        ]);
        $body = $response->getBody(); //获取响应体，对象
        $bodyStr = (string) $body; //对象转字串

        stdLog()->debug('飞书 sendMarkDown：', [$data, $bodyStr]);

        return true;
    }

    private static function getSign(int $timestamp, string $secret): string
    {
        $secret = hash_hmac('sha256', '', $timestamp . "\n" . $secret, true);
        return base64_encode($secret);
    }
}
