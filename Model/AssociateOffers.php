<?php


namespace Macopedia\Allegro\Model;


use Macopedia\Allegro\Api\ProductRepositoryInterface;
use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\ResourceModel\Sale\Offers;

class AssociateOffers
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
     * AssociateOffers constructor.
     * @param Logger $logger
     * @param OfferRepository $offerRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Logger $logger,
        OfferRepository $offerRepository,
        ProductRepositoryInterface $productRepository
    )
    {

        $this->logger = $logger;
        $this->offerRepository = $offerRepository;
        $this->productRepository = $productRepository;
    }

    public function execute()
    {
        try{

                foreach($this->offerRepository->all() as $offer)
                {
                    if($offer->getEan()){
                        $product = $this->productRepository->get($offer->getEan());
                        if($product){
                            $product->setData('allegro_offer_id', ($offer->getId()));
                            $product->save();
                        }
                    }
                }

        }
        catch (\Exception $exception){
            $this->logger->exception($exception);
        }

    }
}
