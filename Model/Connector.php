<?php

/**
 * @category    Tun2U
 * @package     Tun2U_KlaviyoAddons
 * @author      Tun2U Team <dev@tun2u.com>
 * @copyright   Copyright (c) 2022 Tun2U (https://www.tun2u.com)
 * @license     https://opensource.org/licenses/gpl-3.0.html  GNU General Public License (GPL 3.0)
 */

declare(strict_types=1);

namespace Tun2U\KlaviyoAddons\Model;

use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Magento\Framework\Webapi\Rest\Request;

/**
 * Class Connector
 */
class Connector
{
    /**
     * Request timeout
     */
    const TIMEOUT = 100.0;
    const USER_AGENT = 'Klaviyo/1.0';
    const KLAVIYO_HOST = 'https://a.klaviyo.com/api/';

    /**
     * @var ClientFactory
     */
    private $clientFactory;

    /**
     * Klaviyo scope setting helper
     * @var \Klaviyo\Reclaim\Helper\ScopeSetting $klaviyoScopeSetting
     */
    private $klaviyoScopeSetting;

    /**
     * @var \Tun2U\KlaviyoAddons\Logger\Logger
     */
    private $logger;


    /**
     * Connector constructor.
     *
     * @param ClientFactory $clientFactory
     * @param \Klaviyo\Reclaim\Helper\ScopeSetting $klaviyoScopeSetting
     * @param \Tun2U\KlaviyoAddons\Logger\Logger $logger
     */
    public function __construct(
        ClientFactory $clientFactory,
        \Klaviyo\Reclaim\Helper\ScopeSetting $klaviyoScopeSetting,
        \Tun2U\KlaviyoAddons\Logger\Logger $logger
    ) {
        $this->clientFactory = $clientFactory;
        $this->klaviyoScopeSetting = $klaviyoScopeSetting;
        $this->logger = $logger;
    }

    /**
     * Call API service with provided params
     *
     * @param string $service
     * @param array $params
     * @param array|null $urlPrams
     * 
     * @return Response|false
     */
    public function call(string $service, array $params = [], string $requestMethod = Request::HTTP_METHOD_POST, $urlPrams = null)
    {
        $apiKey = $this->klaviyoScopeSetting->getPrivateApiKey();

        $url = self::KLAVIYO_HOST . $service . '?api_key=' . $apiKey;

        if ($urlPrams !== null) {
            $url .= '&' . http_build_query($urlPrams);
        }

        $result = $this->doRequest($url, $params, $requestMethod);

        return $result;
    }

    /**
     * Do API request with provided params
     *
     * @param string $uriEndpoint
     * @param array $params
     * @param string $requestMethod
     * @return Response|false
     */
    private function doRequest(
        string $uriEndpoint,
        array $params,
        string $requestMethod
    ) {

        /** @var Client $client */
        $client = $this->clientFactory->create(['config' => [
            'timeout'  => self::TIMEOUT
        ]]);
        
        $params['headers']['User-Agent'] = self::USER_AGENT;

        try {
            $res = $client->request(
                $requestMethod,
                $uriEndpoint,
                $params
            );

            $responseBody = $res->getBody();
            if ($responseBody) {
                $response = json_decode($responseBody->getContents());
            }
        } catch (GuzzleException $exception) {
            $response = false;
            $this->logger->critical($exception->getMessage());
        }

        return $response;
    }
}
