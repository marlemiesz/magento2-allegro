<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Console\Command;

use Macopedia\Allegro\Model\AbstractOrderImporter;
use Macopedia\Allegro\Model\OrderWithErrorImporterFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * ImportOrdersWithErrors command class
 */
class ImportQuantityOffers extends Command
{
    /**
     * @var State
     */
    private $state;
    /**
     * @var \Macopedia\Allegro\Model\ImportQuantityOffers
     */
    private $importQuantityOffers;

    /**
     * AssociateAuctionsWithProducts constructor.
     * @param State $state
     * @param \Macopedia\Allegro\Model\ImportQuantityOffers $importQuantityOffers
     * @param string|null $name
     */
    public function __construct(
        State $state,
        \Macopedia\Allegro\Model\ImportQuantityOffers $importQuantityOffers,
        string $name = null
    )
    {
        parent::__construct($name);
        $this->state = $state;
        $this->importQuantityOffers = $importQuantityOffers;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('macopedia:allegro:import-quantity-offers');
        $this->setDescription("");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->checkAreaCode();

        $this->importQuantityOffers->execute();



    }

    protected function checkAreaCode(): void
    {
        try {
            $this->state->getAreaCode();
        } catch (LocalizedException $exception) {
            $this->state->setAreaCode(Area::AREA_GLOBAL);
        }
    }


}
