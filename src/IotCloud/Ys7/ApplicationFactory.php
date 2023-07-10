<?php

    declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\IotCloud\Ys7;

    use Bailing\IotCloud\Config;
    use Hyperf\Contract\ConfigInterface;
    use Psr\Container\ContainerInterface;

    class ApplicationFactory
    {
        public function __invoke(ContainerInterface $container)
        {
            $config = $container->get(ConfigInterface::class)->get('ys7');
            file_put_contents('ApplicationFactory.log', print_r(['$config' => $config], true) . PHP_EOL, 8);
            return new Application(new Config($config));
        }
    }
