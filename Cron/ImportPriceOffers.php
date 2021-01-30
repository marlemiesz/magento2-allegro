<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Cron;

use Macopedia\Allegro\Logger\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Macopedia\Allegro\Model\OrderImporter\AllegroReservation;

/**
 * Class responsible for cleaning old reservations
 */
class ImportPriceOffers
{
    const CRON_CONFIG_KEY = 'allegro/competition/competition_cron_enabled';

    /** @var Logger */
    private $logger;

    /** @var ScopeConfigInterface */
    private $scopeConfig;
    /**
     * @var \Macopedia\Allegro\Model\ImportPriceOffers
     */
    private $importPriceOffers;


    /**
     * @param Logger $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param \Macopedia\Allegro\Model\ImportPriceOffers $importPriceOffers
     */
    public function __construct(
        Logger $logger,
        ScopeConfigInterface $scopeConfig,
        \Macopedia\Allegro\Model\ImportPriceOffers $importPriceOffers
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->importPriceOffers = $importPriceOffers;
    }

    public function execute()
    {
        if ($this->scopeConfig->getValue(self::CRON_CONFIG_KEY)) {
            $this->logger->addInfo("Cronjob import price offers is executed.");
            $this->importPriceOffers->execute();
        }
    }
}
