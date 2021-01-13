<?php


namespace Macopedia\Allegro\Model;


use Macopedia\Allegro\Api\ProductRepositoryInterface;
use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\CompetitionImport\Processor;
use Macopedia\Allegro\Model\ResourceModel\Sale\Offers;

class CompetitionImport
{

    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var OfferRepository
     */
    private $offerRepository;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var Processor
     */
    private $processor;

    /**
     * AssociateOffers constructor.
     * @param Logger $logger
     * @param OfferRepository $offerRepository
     * @param ProductRepositoryInterface $productRepository
     * @param Processor $processor
     */
    public function __construct(
        Logger $logger,
        ProductRepositoryInterface $productRepository,
        Processor $processor
    )
    {

        $this->logger = $logger;
        $this->productRepository = $productRepository;
        $this->processor = $processor;
    }

    public function execute()
    {
        try{
            $products = $this->productRepository->getProductWithAllegroSearchKeywordAttribute();
            foreach($products as $product){
                $this->processor->processProduct($product);
            }

        }
        catch (\Exception $exception){
            $this->logger->exception($exception);
        }

    }
}
