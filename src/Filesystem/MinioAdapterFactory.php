<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\Filesystem;

use Hyperf\Filesystem\Contract\AdapterFactoryInterface;

class MinioAdapterFactory implements AdapterFactoryInterface
{
    public function make(array $options)
    {
        return new MinioAdapter($options);
    }
}
