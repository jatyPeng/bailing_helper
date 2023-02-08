<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class SystemPermission extends AbstractAnnotation
{
    public string $module = ''; // 管理后台:系统设置:角色管理

    public string $action = ''; // 查看、增加、修改、删除

    public string $icon = ''; // 参考: http://layuimini.99php.cn/iframe/v2/index.html#/page/icon.html

    public string $menu_type = '1'; // 1展示，0归类

    public string $url_type = 'path'; // 	URL类别(path, frame_url, target_url)

    public string $alias = ''; // 路由别名

    public string $param = ''; // 路由参数

    public string $sort = '0'; // 排序，越大越前，一级菜单千进位，二级菜单百进位，默认0

    public string $status = '1'; // 状态，0和1，默认1

    public string $app = ''; // 微前端提供者
}
