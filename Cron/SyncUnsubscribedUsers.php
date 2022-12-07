<?php

/**
 * @category    Tun2U
 * @package     Tun2U_KlaviyoAddons
 * @author      Tun2U Team <dev@tun2u.com>
 * @copyright   Copyright (c) 2022 Tun2U (https://www.tun2u.com)
 * @license     https://opensource.org/licenses/gpl-3.0.html  GNU General Public License (GPL 3.0)
 */

namespace Tun2U\KlaviyoAddons\Cron;


class SyncUnsubscribedUsers
{
	/**
	 * @var \Tun2U\KlaviyoAddons\Service\KlaviyoService
	 */
	protected $klaviyoService;

	/**
     * SyncUnsubscribedUsers constructor.
     * 
	 * @param \Tun2U\KlaviyoAddons\Service\KlaviyoService $klaviyoService
	 */
	public function __construct(
		\Tun2U\KlaviyoAddons\Service\KlaviyoService $klaviyoService
	)
    {
		$this->klaviyoService = $klaviyoService;
	}

	/**
     *  Execute sync to unsubscribe all users wich wehere unsubscribed from klaviyo
     *
     * @return void
     */
	public function execute()
	{
		$this->klaviyoService->syncUnsubscribedUsers();
	}
}