<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Cron;

use Macopedia\Allegro\Logger\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Macopedia\Allegro\Model\OrderImporter\AllegroReservation;

/**
 * Class responsible for cleaning old reservations
 */
class CompetitionsSendToGoogleSheet
{
    const CRON_CONFIG_KEY = 'allegro/competition/competition_cron_enabled';

    /** @var Logger */
    private $logger;

    /** @var ScopeConfigInterface */
    private $scopeConfig;
    /**
     * @var \Macopedia\Allegro\Model\CompetitionsSendToGoogleSheet
     */
    private $competitionsSendToGoogleSheet;


    /**
     * @param Logger $logger
     * @param ScopeConfigInterface $scopeConfig
     * @param \Macopedia\Allegro\Model\CompetitionsSendToGoogleSheet $competitionsSendToGoogleSheet
     */
    public function __construct(
        Logger $logger,
        ScopeConfigInterface $scopeConfig,
        \Macopedia\Allegro\Model\CompetitionsSendToGoogleSheet $competitionsSendToGoogleSheet
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->competitionsSendToGoogleSheet = $competitionsSendToGoogleSheet;
    }

    public function execute()
    {
        if ($this->scopeConfig->getValue(self::CRON_CONFIG_KEY)) {
            $this->logger->addInfo("Cronjob competitions send to google sheet is executed.");
            $this->competitionsSendToGoogleSheet->execute();
        }
    }
}
