<?php

    declare(strict_types=1);
/**
 * This file is part of Kuaijing Bailing.
 *
 * @link     https://www.kuaijingai.com
 * @document https://help.kuaijingai.com
 * @contact  www.kuaijingai.com 7*12 9:00-21:00
 */
namespace Bailing\IotCloud\Kernel;

    use GuzzleHttp\Client;
    use Hyperf\Utils\Codec\Json;

    trait HttpClient
    {
        protected $guzzleOptions = [];

        protected ?string $baseUri = null;

        public function request($method, $endpoint, $options = [])
        {
            return $this->unwrapResponse($this->getHttpClient()->{$method}($endpoint, $options));
        }

        public function setGuzzleOptions(array $options)
        {
            $this->guzzleOptions = $options;
        }

        public function setBaseUri($uri)
        {
            $this->baseUri = trim($uri, '/');
            return $this;
        }

        protected function getHttpClient(): Client
        {
            $this->guzzleOptions['base_uri'] = $this->baseUri;
            return new Client($this->guzzleOptions);
        }

        /**
         * 统一转换响应结果为 json 格式.
         * @param $response
         *
         * @return mixed
         */
        protected function unwrapResponse($response)
        {
            $contentType = $response->getHeaderLine('Content-Type');
            $contents = $response->getBody()->getContents();
            if (stripos($contentType, 'json') !== false || stripos($contentType, 'javascript')) {
                return \json_decode($contents, true);
            }
            if (stripos($contentType, 'xml') !== false) {
                return \json_decode(\json_encode(\simplexml_load_string($contents)), true);
            }

            return $contents;
        }
    }
