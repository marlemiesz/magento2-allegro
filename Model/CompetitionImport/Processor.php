<?php


namespace Macopedia\Allegro\Model\CompetitionImport;


use Macopedia\Allegro\Api\CompetitionRepositoryInterface;
use Macopedia\Allegro\Api\Data\CompetitionInterface;
use Macopedia\Allegro\Api\Data\CompetitionModelInterface;
use Macopedia\Allegro\Model\Competition;
use Macopedia\Allegro\Model\OfferRepository;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\ObjectManagerInterface;

class Processor
{
    /**
     * @var SearchKeywordDecoder
     */
    private $searchKeywordDecoder;
    /**
     * @var CompetitionRepositoryInterface
     */
    private $competitionRepository;
    /**
     * @var OfferRepository
     */
    private $offerRepository;
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;


    /**
     * Processor constructor.
     * @param SearchKeywordDecoder $searchKeywordDecoder
     * @param CompetitionRepositoryInterface $competitionRepository
     * @param OfferRepository $offerRepository
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        SearchKeywordDecoder $searchKeywordDecoder,
        CompetitionRepositoryInterface $competitionRepository,
        OfferRepository $offerRepository,
        ObjectManagerInterface $objectManager
    )
    {
        $this->searchKeywordDecoder = $searchKeywordDecoder;
        $this->competitionRepository = $competitionRepository;
        $this->offerRepository = $offerRepository;
        $this->objectManager = $objectManager;
    }

    /**
     * @param ProductInterface $product
     */
    public function processProduct(ProductInterface $product)
    {
        foreach($this->searchKeywordDecoder->decode($product->getData('allegro_search_keyword')) as $keyword){
            $competitions = $this->offerRepository->competitionAll($keyword);
            $this->processCompetitions($competitions, $product);
        }
    }

    /**
     * @param array $competitions
     * @param ProductInterface $product
     */
    public function processCompetitions(array $competitions, ProductInterface $product)
    {
        foreach($competitions as $competition)
        {
            $this->processCompetition($competition, $product);
        }
    }

    /**
     * @param CompetitionInterface $competition
     * @param ProductInterface $product
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function processCompetition(CompetitionInterface $competition, ProductInterface $product)
    {
        $competitionModel = $this->objectManager->create(CompetitionModelInterface::class);
        $competitionModel->setAllegroAuctionId($competition->getId());
        $competitionModel->setProductId($product->getId());
        $competitionModel->setPrice((float) $competition->getPrice());
        $competitionModel->setQuantity((int) $competition->getQty());
        $competitionModel->setName($competition->getName());
        $competitionModel->setImage($competition->getImage());

        $this->competitionRepository->save($competitionModel);
        die();
    }

}
