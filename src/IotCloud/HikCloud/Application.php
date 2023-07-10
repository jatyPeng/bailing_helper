<?php

declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\IotCloud\HikCloud;

use Bailing\IotCloud\Config;
use Bailing\IotCloud\Exception\InvalidArgumentException;
use Bailing\IotCloud\HikCloud\Provider\AdProvider;
use Bailing\IotCloud\HikCloud\Provider\AuthProvider;
use Bailing\IotCloud\HikCloud\Provider\BuildingProvider;
use Bailing\IotCloud\HikCloud\Provider\CommunitProvider;
use Bailing\IotCloud\HikCloud\Provider\DeviceProvider;
use Bailing\IotCloud\HikCloud\Provider\FaceDBProvider;
use Bailing\IotCloud\HikCloud\Provider\MsgProvider;
use Bailing\IotCloud\HikCloud\Provider\PersonProvider;
use Bailing\IotCloud\HikCloud\Provider\PropertyProvider;

/**
 * Class Application.
 * @property AuthProvider $auth
 * @property DeviceProvider $device
 * @property CommunitProvider $communit
 * @property BuildingProvider $building
 * @property PersonProvider $person
 * @property PropertyProvider $property
 * @property AdProvider $ad
 * @property FaceDBProvider $facedb
 * @property MsgProvider $msg
 *
 * @version 1.0.0
 */
class Application
{
    protected array $alias = [
        'auth' => AuthProvider::class,
        'device' => DeviceProvider::class,
        'communit' => CommunitProvider::class,
        'building' => BuildingProvider::class,
        'person' => PersonProvider::class,
        'property' => PropertyProvider::class,
        'ad' => AdProvider::class,
        'facedb' => FaceDBProvider::class,
        'msg' => MsgProvider::class,
    ];

    protected array $providers = [];

    public function __construct(protected Config $config)
    {
    }

    public function __get($name)
    {
        if (! isset($name) || ! $this->alias[$name]) {
            throw new InvalidArgumentException("{$name} is invalid.");
        }

        if (isset($this->providers[$name])) {
            return $this->providers[$name];
        }

        $class = $this->alias[$name];
        return $this->providers[$name] = new $class($this, $this->config);
    }
}
