<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\JsonRpc\Org;

interface OrgMenuServiceInterface
{
    /**
     * 添加菜单.
     */
    public function addMenu(array $menuData): array;

    public function getMenu(array $nowUser, string $micro): array;
}
