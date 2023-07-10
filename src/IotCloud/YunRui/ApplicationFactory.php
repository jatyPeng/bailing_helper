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
use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class ApplicationFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class)->get('yunrui');
        return new Application(new Config($config));
    }
}
