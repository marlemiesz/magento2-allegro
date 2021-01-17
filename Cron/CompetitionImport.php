<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Cron;

use Macopedia\Allegro\Logger\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Macopedia\Allegro\Model\OrderImporter\AllegroReservation;

/**
 * Class responsible for cleaning old reservations
 */
class CompetitionImport
{
    const CRON_CONFIG_KEY = 'allegro/competition/competition_cron_enabled';

    /** @var Logger */
    private $logger;

    /** @var ScopeConfigInterface */
    private $scopeConfig;
    /**
     * @var \Macopedia\Allegro\Model\CompetitionImport
     */
    private $competitionImport;


    /**
     * @param Logger $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param \Macopedia\Allegro\Model\CompetitionImport $competitionImport
     */
    public function __construct(
        Logger $logger,
        ScopeConfigInterface $scopeConfig,
        \Macopedia\Allegro\Model\CompetitionImport $competitionImport
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->competitionImport = $competitionImport;
    }

    public function execute()
    {
        if ($this->scopeConfig->getValue(self::CRON_CONFIG_KEY)) {
            $this->logger->addInfo("Cronjob competition import is executed.");
            $this->competitionImport->execute();
        }
    }
}
