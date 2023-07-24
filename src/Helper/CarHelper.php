<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Helper;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Codec\Json;

class CarHelper
{
    /**
     * 获取车辆品牌列表，缓存7天.
     * @throws Exception
     */
    #[Cacheable(prefix: 'ParkCarBrandList', ttl: 604800, listener: 'ParkCarBrandList-update')]
    public static function carBrandList()
    {
        $client = new Client();
        $url = 'http://customer.kuaijingai.com/park/car/brand?domain=' . RequestHelper::getClientDomain();
        try {
            $res = $client->request('GET', $url);
        } catch (GuzzleException $e) {
            throw new Exception('车辆品牌列表访问失败（' . $url . '）：' . $e->getMessage(), ApiHelper::NORMAL_ERROR);
        }

        $body = (string) $res->getBody(); //获取响应体，对象
        $bodyArr = Json::decode($body, true);
        if ($bodyArr['code'] != ApiHelper::NORMAL_SUCCESS) {
            throw new Exception('车辆品牌列表访问失败（' . $url . '）：' . $bodyArr['code'] . '：' . $bodyArr['msg'], ApiHelper::NORMAL_ERROR);
        }

        return $bodyArr['data']['list'];
    }

    /**
     * 获取车辆品牌列表，缓存7天.
     * @throws Exception
     */
    #[Cacheable(prefix: 'ParkCarSeriesList', value: '_#{brandId}', ttl: 604800, listener: 'ParkCarSeriesList-update')]
    public static function carSeriesList(int $brandId)
    {
        $client = new Client();
        $url = 'http://customer.kuaijingai.com/park/car/series?brand_id=' . $brandId . '&domain=' . RequestHelper::getClientDomain();
        try {
            $res = $client->request('GET', $url);
        } catch (GuzzleException $e) {
            throw new Exception('车系列表访问失败（' . $url . '）：' . $e->getMessage(), ApiHelper::NORMAL_ERROR);
        }

        $body = (string) $res->getBody(); //获取响应体，对象
        $bodyArr = Json::decode($body, true);
        if ($bodyArr['code'] != ApiHelper::NORMAL_SUCCESS) {
            throw new Exception('车系列表访问失败（' . $url . '）：' . $bodyArr['code'] . '：' . $bodyArr['msg'], ApiHelper::NORMAL_ERROR);
        }

        return $bodyArr['data']['list'];
    }
}
