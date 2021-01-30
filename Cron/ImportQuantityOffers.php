<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Cron;

use Macopedia\Allegro\Logger\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Macopedia\Allegro\Model\OrderImporter\AllegroReservation;

/**
 * Class responsible for cleaning old reservations
 */
class ImportQuantityOffers
{

    /** @var Logger */
    private $logger;

    /** @var ScopeConfigInterface */
    private $scopeConfig;
    /**
     * @var \Macopedia\Allegro\Model\ImportQuantityOffers
     */
    private $importQuantityOffers;


    /**
     * @param Logger $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param \Macopedia\Allegro\Model\ImportQuantityOffers $importQuantityOffers
     */
    public function __construct(
        Logger $logger,
        ScopeConfigInterface $scopeConfig,
        \Macopedia\Allegro\Model\ImportQuantityOffers $importQuantityOffers
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->importQuantityOffers = $importQuantityOffers;
    }

    public function execute()
    {
        $this->logger->addInfo("Cronjob import quantity offers is executed.");
        $this->importQuantityOffers->execute();
    }
}
