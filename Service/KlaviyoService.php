<?php

/**
 * @category    Tun2U
 * @package     Tun2U_KlaviyoAddons
 * @author      Tun2U Team <dev@tun2u.com>
 * @copyright   Copyright (c) 2022 Tun2U (https://www.tun2u.com)
 * @license     https://opensource.org/licenses/gpl-3.0.html  GNU General Public License (GPL 3.0)
 */

declare(strict_types=1);

namespace Tun2U\KlaviyoAddons\Service;

use Magento\Framework\Webapi\Rest\Request;
use Exception;

/**
 * Class KlaviyoService
 */
class KlaviyoService
{

    /**
     * @var \Tun2U\KlaviyoAddons\Model\Connector
     */
    private $connector;

    /**
     * @var \Tun2U\KlaviyoAddons\Model\Subscriber
     */
    private $subscriber;

    /**
     * @var \Tun2U\KlaviyoAddons\Logger\Logger
     */
    private $logger;

    /**
     * KlaviyoService constructor.
     *
     * @param \Tun2U\KlaviyoAddons\Model\Connector $connector,
     * 
     */
    public function __construct(
        \Tun2U\KlaviyoAddons\Model\Connector $connector,
        \Tun2U\KlaviyoAddons\Model\Subscriber $subscriber,
        \Tun2U\KlaviyoAddons\Logger\Logger $logger
    ) {
        $this->connector = $connector;
        $this->subscriber = $subscriber;
        $this->logger = $logger;
    }

    /**
     * Gets all unsubscribed users from klaviyo and unsubscribes them from magento
     * 
     * @return void
     */
    public function syncUnsubscribedUsers()
    {
        $urlPrams = [
            'sort' => 'desc',
            'count' => 500,
            'page' => 0
        ];

        $this->doUnsubscribe($urlPrams);
    }

    private function doUnsubscribe(array $urlPrams)
    {
        $params = [
            'headers' => [
                'accept' => 'application/json'
            ]
        ];
        // get unsubscribed users from klaviyo
        $response = $this->connector->call('v1/people/exclusions', $params, Request::HTTP_METHOD_GET, $urlPrams);

        try {
            if (count($response->data) > 0) {
                $unsubscribedUsers = $response->data;
                foreach ($unsubscribedUsers as $user) {
                    $email = $user->email;
                    $this->subscriber->unsubscribeCustomerByEmail($email);
                }
                // get the next 500 unsubscribed users
                $newUrlPrams = $urlPrams;
                $newUrlPrams['page'] = $urlPrams['page'] + 1;
                $this->doUnsubscribe($newUrlPrams);
            }
        } catch (\Exception $e) {
            echo $e->getMessage()."\n";
            $this->logger->critical($e->getMessage());
        }
    }
}
