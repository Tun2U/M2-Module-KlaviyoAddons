<?php

/**
 * @category    Tun2U
 * @package     Tun2U_KlaviyoAddons
 * @author      Tun2U Team <dev@tun2u.com>
 * @copyright   Copyright(c) 2022 Tun2U (https://www.tun2u.com)
 * @license     https://opensource.org/licenses/gpl-3.0.html  GNU General Public License (GPL 3.0)
 */

namespace Tun2U\KlaviyoAddons\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class Commands extends Command
{
    /**
     * @var \Tun2U\KlaviyoAddons\Service\KlaviyoService
     */
    private $klaviyoService;

    /**
     * Commands constructor.
     *
     * @param \Tun2U\KlaviyoAddons\Service\KlaviyoService $klaviyoService
     */
    public function __construct(
        \Tun2U\KlaviyoAddons\Service\KlaviyoService $klaviyoService
    ) {
        $this->klaviyoService = $klaviyoService;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('tun2u:klaviyoaddons')
            ->setDescription('Klaviyo addons')
            ->addOption('action', "action", InputOption::VALUE_REQUIRED, "Specific Action (sync_unsubscribed_users)");
        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $action = null;

        try {
            $action = $input->getOption('action');

            if ($action == 'sync_unsubscribed_users') {
                $result = $this->klaviyoService->syncUnsubscribedUsers();
                $output->writeln($result);
            }
        } catch (\InvalidArgumentException $e) {
            $output->writeln('<error>Invalid argument.</error>');
        }
    }
}
