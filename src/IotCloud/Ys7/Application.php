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
    use Bailing\IotCloud\Exception\InvalidArgumentException;
    use Bailing\IotCloud\Ys7\Provider\AccountProvider;
    use Bailing\IotCloud\Ys7\Provider\AiFaceProvider;
    use Bailing\IotCloud\Ys7\Provider\AuthProvider;
    use Bailing\IotCloud\Ys7\Provider\BuildingProvider;
    use Bailing\IotCloud\Ys7\Provider\CloudProvider;
    use Bailing\IotCloud\Ys7\Provider\DetectorProvider;
    use Bailing\IotCloud\Ys7\Provider\DeviceProvider;
    use Bailing\IotCloud\Ys7\Provider\HelmetProvider;
    use Bailing\IotCloud\Ys7\Provider\HubProvider;
    use Bailing\IotCloud\Ys7\Provider\KeyLockProvider;
    use Bailing\IotCloud\Ys7\Provider\LiveProvider;
    use Bailing\IotCloud\Ys7\Provider\OcrCarProvider;
    use Bailing\IotCloud\Ys7\Provider\OcrHumanProvider;
    use Bailing\IotCloud\Ys7\Provider\OcrTextProvider;
    use Bailing\IotCloud\Ys7\Provider\PassengerflowProvider;
    use Bailing\IotCloud\Ys7\Provider\PtzProvider;
    use Bailing\IotCloud\Ys7\Provider\SettingProvider;
    use Bailing\IotCloud\Ys7\Provider\VoiceProvider;

    /**
     * Class Application.
     *
     * @property AuthProvider $auth
     * @property DeviceProvider $device
     * @property LiveProvider $live
     * @property SettingProvider $setting
     * @property VoiceProvider $voice
     * @property PtzProvider $ptz
     * @property DetectorProvider $detector
     * @property PassengerflowProvider $passengerflow
     * @property CloudProvider $cloud
     * @property BuildingProvider $building
     * @property KeyLockProvider $keylock
     * @property HubProvider $hub
     * @property AccountProvider $account
     * @property OcrTextProvider $ocrText
     * @property OcrHumanProvider $ocrHuman
     * @property AiFaceProvider $aiFace
     * @property OcrCarProvider $ocrCar
     * @property HelmetProvider $helmet
     *
     * @version 1.0.0
     */
    class Application
    {
        protected array $alias = [
            'auth' => AuthProvider::class,
            'device' => DeviceProvider::class,
            'live' => LiveProvider::class,
            'setting' => SettingProvider::class,
            'voice' => VoiceProvider::class,
            'ptz' => PtzProvider::class,
            'detector' => DetectorProvider::class,
            'passengerflow' => PassengerflowProvider::class,
            'cloud' => CloudProvider::class,
            'building' => BuildingProvider::class,
            'keylock' => KeyLockProvider::class,
            'hub' => HubProvider::class,
            'account' => AccountProvider::class,
            'ocrText' => OcrTextProvider::class,
            'ocrHuman' => OcrHumanProvider::class,
            'aiFace' => AiFaceProvider::class,
            'ocrCar' => OcrCarProvider::class,
            'helmet' => HelmetProvider::class,
        ];

        protected array $providers = [];

        public function __construct(protected Config $config)
        {
        }

        public function __get($name)
        {
            if (! isset($name) || ! isset($this->alias[$name])) {
                throw new InvalidArgumentException("{$name} is invalid.");
            }

            if (isset($this->providers[$name])) {
                return $this->providers[$name];
            }

            $class = $this->alias[$name];

            return $this->providers[$name] = new $class($this, $this->config);
        }
    }
