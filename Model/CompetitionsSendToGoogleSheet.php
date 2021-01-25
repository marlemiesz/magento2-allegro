<?php


namespace Macopedia\Allegro\Model;


use Google\Exception;
use Macopedia\Allegro\Api\CompetitionRepositoryInterface;
use Macopedia\Allegro\Model\Google\Sheet;
use Macopedia\Allegro\Model\Google\Sheet\Collection\CompetitionCollection;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

class CompetitionsSendToGoogleSheet
{
    const CREDENTIALS_CONFIG_KEY = 'allegro/competition/competition_google_credentials';
    const SHEET_ID_CONFIG_KEY = 'allegro/competition/competition_google_sheet_id';
    const SHEET_NAME_CONFIG_KEY = 'allegro/competition/competition_google_sheet_name';

    const IS_COMPETITION_STATUS = 'Tak';
    const IS_NOT_COMPETITION_STATUS = 'Nie';
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var Sheet
     */
    private $google_sheet;
    /**
     * @var CompetitionCollection
     */
    private $competitionCollection;
    /**
     * @var CompetitionRepositoryInterface
     */
    private $competitionRepository;
    /**
     * @var ProductFactory
     */
    private $productFactory;
    /**
     * @var Image
     */
    private $image;

    /**
     * CompetitionsSendToGoogleSheet constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param CompetitionRepositoryInterface $competitionRepository
     * @param ProductFactory $productFactory
     * @param Image $image
     * @throws Exception
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        CompetitionRepositoryInterface $competitionRepository,
        ProductFactory $productFactory,
        Image $image
    )
    {
        $this->scopeConfig = $scopeConfig;

        $this->google_sheet = new Sheet(
            $this->scopeConfig->getValue(self::CREDENTIALS_CONFIG_KEY),
            $this->scopeConfig->getValue(self::SHEET_ID_CONFIG_KEY),
            $this->scopeConfig->getValue(self::SHEET_NAME_CONFIG_KEY)
        );

        $this->competitionCollection = new CompetitionCollection();

        $this->competitionRepository = $competitionRepository;
        $this->productFactory = $productFactory;
        $this->image = $image;
    }

    public function execute()
    {
        $this->readCompetitions();
        $this->google_sheet->clear(count($this->google_sheet->getData('A2:Z')),'A2:Z');
        $this->updateCompetitions();

        $this->collectNotChooseCompetitions();

        $this->google_sheet->setCompetitions($this->competitionCollection,'A2:Z');

        $this->setProductPrice();


    }

    protected function updateCompetitions()
    {
        foreach ($this->competitionCollection->getItemsWithStatus() as $competition)
        {
            if($competition->getStatus() === self::IS_COMPETITION_STATUS)
            {
                $this->competitionRepository->setCompetitionByProductIdAndAuctionId($competition->getProductId(), $competition->getAuctionId());
            }
            if($competition->getStatus() === self::IS_NOT_COMPETITION_STATUS)
            {
                $this->competitionRepository->setNotCompetitionByProductIdAndAuctionId($competition->getProductId(), $competition->getAuctionId());
            }
            $this->competitionCollection->remove($competition);
        }
    }

    /**
     *
     */
    protected function readCompetitions(): void
    {
        $items = $this->google_sheet->getData('A2:Z');
        if($items){
            foreach ($items as $item) {
                if(isset($item[0]) && $item[0]){
                    $competition = new Sheet\Model\Competition();
                    $competition->setRaw($item);
                    $this->competitionCollection->add($competition);
                }

            }
        }

    }

    /**
     * @param int $id
     * @return string
     */
    protected function getProductImageUrl(int $id): string
    {
        $product = $this->productFactory->create()->load($id);
        foreach($product->getMediaGalleryImages() as $image){
            return $image['url'];
        }
    }

    protected function collectNotChooseCompetitions(): void
    {
        $allegroAuctions = $this->competitionRepository->getNotChooseAllegroAuction();
        foreach ($allegroAuctions as $allegroAuction) {
            $competition = new Sheet\Model\Competition(
                $allegroAuction->getProductId(),
                $allegroAuction->getAllegroAuctionId(),
                $allegroAuction->getImage(),
                $allegroAuction->getName(),
                $allegroAuction->getPrice(),
                $allegroAuction->getQuantity(),
                $this->getProductImageUrl($allegroAuction->getProductId())
            );
            $this->competitionCollection->add($competition);
        }
    }

    protected function setProductPrice(): void
    {
        $products = $this->competitionRepository->getCompetitionMinPriceGroupByProduct();

        foreach ($products as $product_id => $price) {
            $product = $this->productFactory->create()->load($product_id);
            $product->setData('allegro_competition_minimal_price', $price);
            $product->save();
        }
    }


}
