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
use Bailing\IotCloud\Exception\InvalidArgumentException;
use Bailing\IotCloud\YunRui\Provider\AccountProvider;
use Bailing\IotCloud\YunRui\Provider\AscProvider;
use Bailing\IotCloud\YunRui\Provider\AuthProvider;
use Bailing\IotCloud\YunRui\Provider\BuildingProvider;
use Bailing\IotCloud\YunRui\Provider\DeviceProvider;
use Bailing\IotCloud\YunRui\Provider\LiveProvider;
use Bailing\IotCloud\YunRui\Provider\MixedProvider;
use Bailing\IotCloud\YunRui\Provider\MixProvider;
use Bailing\IotCloud\YunRui\Provider\MsgProvider;
use Bailing\IotCloud\YunRui\Provider\OrgProvider;
use Bailing\IotCloud\YunRui\Provider\PersonProvider;

/**
 * Class Application.
 *
 * @property AuthProvider $auth
 * @property DeviceProvider $device
 * @property PersonProvider $person
 * @property LiveProvider $live
 * @property MixedProvider $mixed
 * @property MixProvider $mix
 * @property AscProvider $asc
 * @property OrgProvider $org
 * @property MsgProvider $msg
 * @property BuildingProvider $building
 * @property AccountProvider $account
 *
 * @version 1.0.0
 */
class Application
{
    protected array $alias = [
        'auth' => AuthProvider::class,
        'device' => DeviceProvider::class,
        'person' => PersonProvider::class,
        'live' => LiveProvider::class,
        'mixed' => MixedProvider::class,
        'mix' => MixedProvider::class,
        'asc' => AscProvider::class,
        'org' => OrgProvider::class,
        'msg' => MsgProvider::class,
        'building' => BuildingProvider::class,
        'account' => AccountProvider::class,
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
