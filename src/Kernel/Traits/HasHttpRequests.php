<?php

/*
 * This file is part of the overtrue/http.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace EasyDingTalk\Kernel\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
/**
 * Trait HasHttpRequests.
 */
trait HasHttpRequests
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * @var array
     */
    protected static $defaults = [
        'curl' => [
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ],
    ];

    /**
     * Set guzzle default settings.
     *
     * @param array $defaults
     */
    public static function setDefaultOptions($defaults = [])
    {
        self::$defaults = $defaults;
    }

    /**
     * Return current guzzle default settings.
     *
     * @return array
     */
    public static function getDefaultOptions(): array
    {
        return self::$defaults;
    }

    /**
     * Set GuzzleHttp\Client.
     *
     * @param \GuzzleHttp\ClientInterface $httpClient
     *
     * @return $this
     */
    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * Return GuzzleHttp\Client instance.
     *
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        if (!$this->httpClient) {
            $this->httpClient = new Client(['handler' => HandlerStack::create($this->getGuzzleHandler())]);
        }

        return $this->httpClient;
    }

    /**
     * Make a request.
     *
     * @param string $uri
     * @param string $method
     * @param array  $options
     * @param bool   $async
     *
     * @return \Psr\Http\Message\ResponseInterface|\GuzzleHttp\Promise\Promise
     */
    public function request($uri, $method = 'GET', $options = [], bool $async = false)
    {
        return $this->getHttpClient()->{ $async ? 'requestAsync' : 'request' }(strtoupper($method), $uri, array_merge(self::$defaults, $options));
    }

    /**
     * Get guzzle handler.
     *
     * @return callable
     */
    protected function getGuzzleHandler()
    {
        if (property_exists($this, 'app') && isset($this->app['guzzle_handler'])) {
            return is_string($handler = $this->app->raw('guzzle_handler'))
                ? new $handler()
                : $handler;
        }

        return \GuzzleHttp\choose_handler();
    }
}
