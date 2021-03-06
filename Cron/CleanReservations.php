<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Cron;

use Macopedia\Allegro\Logger\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Macopedia\Allegro\Model\OrderImporter\AllegroReservation;

/**
 * Class responsible for cleaning old reservations
 */
class CleanReservations
{
    const CRON_CONFIG_KEY = 'allegro/order/competition_cron_enabled';

    /** @var Logger */
    private $logger;

    /** @var ScopeConfigInterface */
    private $scopeConfig;


    /**
     * @param Logger $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param AllegroReservation $allegroReservation
     */
    public function __construct(
        Logger $logger,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        if ($this->scopeConfig->getValue(self::CRON_CONFIG_KEY)) {
            $this->logger->addInfo("Cronjob search competition auctions is executed.");
        }
    }
}
