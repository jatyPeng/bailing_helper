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

class LinkLibraryHelper
{
    /**
     * 按名称获取配置项值.
     * @param string $url 短链接标记
     * @param string $port 链接的端
     */
    public static function getLink(string $url, string $port = 'user'): array
    {
        $urlArr = explode('?', $url);

        $alias = $urlArr[0];

        $linkArr = config('linkLibrary');

        //若存在值，则返回
        $key = $port . '_' . $alias;
        if (isset($linkArr[$key])) {
            if ($port == 'user') {
                $linkArr[$key]['link_url'] = $linkArr[$key]['link'] ? (domain() . '/h5' . $linkArr[$key]['link'] . ($urlArr[1] ? '?' . $urlArr[1] : '')) : '';
                $linkArr[$key]['pc_link_url'] = $linkArr[$key]['pc_link'] ? (domain() . $linkArr[$key]['pc_link'] . ($urlArr[1] ? '?' . $urlArr[1] : '')) : '';
            } else {  //org
                $linkArr[$key]['link_url'] = $linkArr[$key]['link'] ? (domain() . '/h5_orgs' . $linkArr[$key]['link'] . ($urlArr[1] ? '?' . $urlArr[1] : '')) : '';
                $linkArr[$key]['pc_link_url'] = $linkArr[$key]['pc_link'] ? (domain() . $linkArr[$key]['pc_link'] . ($urlArr[1] ? '?' . $urlArr[1] : '')) : '';
            }

            return $linkArr[$key];
        }

        return [
            'link' => '',
            'link_url' => '',
            'pc_link' => '',
            'pc_link_url' => '',
        ];
    }
}
