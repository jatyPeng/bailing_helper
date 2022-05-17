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

use GuzzleHttp\Client;
use Hyperf\Utils\Codec\Json;

/**
 * QQ地图操作类.
 */
class QqmapHelper
{
    /**
     * 周边搜索.
     * @param float $lat 纬度
     * @param float $lng 经度
     * @param string $category 分类
     * @param int $radius 半径
     */
    public static function lbsExplore(float $lat, float $lng, string $category = '', int $radius = 1000): array
    {
        $url = 'https://apis.map.qq.com/ws/place/v1/explore';

        $clientHttp = new Client();
        $response = $clientHttp->get($url, [
            'query' => [
                'boundary' => 'nearby(' . $lat . ',' . $lng . ',' . $radius . ',1)',
                'filter' => 'category=' . $category,
                'page_size' => 20,
                'orderby' => '_distance',
                'address_format' => 'short',
                'key' => cfg('qq_map_key') ?: 'XY3BZ-3IKCP-2WADY-VABVN-QYDNO-BRBDL',
                'output' => 'json',
            ],
        ]);

        $body = $response->getBody(); //获取响应体，对象
        $bodyStr = (string) $body; //对象转字串

        return Json::decode($bodyStr, true);
    }

    /**
     * 城市/区域搜索地点.
     * @param string $keyword 关键词
     * @param int|string $city 城市名或行政区划代码
     */
    public static function placeSearch(string $keyword, string|int $city): array
    {
        $url = 'https://apis.map.qq.com/ws/place/v1/search';

        $clientHttp = new Client();
        $response = $clientHttp->get($url, [
            'query' => [
                'boundary' => 'region(' . $city . ')',
                'keyword' => $keyword,
                'page_size' => 20,
                'key' => cfg('qq_map_key') ?: 'XY3BZ-3IKCP-2WADY-VABVN-QYDNO-BRBDL',
                'output' => 'json',
            ],
        ]);

        $body = $response->getBody(); //获取响应体，对象
        $bodyStr = (string) $body; //对象转字串

        return Json::decode($bodyStr, true);
    }
}
