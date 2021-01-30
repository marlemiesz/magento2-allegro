<?php


namespace Macopedia\Allegro\Model;


use Macopedia\Allegro\Api\ProductRepositoryInterface;
use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\ResourceModel\Sale\Offers;

class ImportQuantityOffers
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

        foreach($this->offerRepository->all() as $offer)
        {
            try {
                $product = $this->productRepository->getByAllegroOfferId((int)$offer->getId());
                if ($product) {
                    $product->setStockData(
                        [
                            'use_config_manage_stock' => 0,
                            'qty' => $offer->getQty(),
                            'is_qty_decimal' => 0,
                            'manage_stock' => 1,
                            'is_in_stock' => (bool)$offer->getQty()
                        ]
                    );
                    $product->save();
                }
            }
            catch (\Exception $exception){
                $this->logger->exception($exception);
            }


        }


    }
}
