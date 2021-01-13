<?php

namespace Macopedia\Allegro\Model\Data;

use Macopedia\Allegro\Api\Data\CompetitionInterface;
use Macopedia\Allegro\Api\Data\ImageInterfaceFactory;
use Macopedia\Allegro\Api\Data\Offer\AfterSalesServicesInterfaceFactory;
use Macopedia\Allegro\Api\Data\Offer\LocationInterfaceFactory;
use Macopedia\Allegro\Api\ParameterDefinitionRepositoryInterface;
use Magento\Framework\DataObject;

class Competition extends DataObject implements CompetitionInterface
{

    const ID_FIELD_NAME = 'id';
    const NAME_FIELD_NAME = 'name';
    const QTY_FIELD_NAME = 'qty';
    const PRICE_FIELD_NAME = 'price';
    const IMAGE_FIELD_NAME = 'image';
    const LOGIN_FIELD_ID = 'image';

    /** @var ParameterDefinitionRepositoryInterface */
    private $parameterDefinitionRepository;

    /** @var ImageInterfaceFactory */
    private $imageFactory;

    /** @var LocationInterfaceFactory */
    private $locationFactory;

    /** @var AfterSalesServicesInterfaceFactory */
    private $afterSalesServicesFactory;

    /**
     * Offer constructor.
     * @param ParameterDefinitionRepositoryInterface $parameterDefinitionRepository
     * @param ImageInterfaceFactory $imageFactory
     * @param LocationInterfaceFactory $locationFactory
     * @param AfterSalesServicesInterfaceFactory $afterSalesServicesFactory
     * @throws \Macopedia\Allegro\Model\Api\ClientException
     */
    public function __construct(
        ParameterDefinitionRepositoryInterface $parameterDefinitionRepository
    ) {
        $this->parameterDefinitionRepository = $parameterDefinitionRepository;
    }

    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id)
    {
        $this->setData(self::ID_FIELD_NAME, $id);
    }



    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name)
    {
        $this->setData(self::NAME_FIELD_NAME, $name);
    }





    /**
     * @param int $qty
     * @return void
     */
    public function setQty(int $qty)
    {
        $this->setData(self::QTY_FIELD_NAME, $qty);
    }

    /**
     * @param float $price
     * @return void
     */
    public function setPrice(float $price)
    {
        $this->setData(self::PRICE_FIELD_NAME, $price);
    }


    /**
     * @param string $image
     */
    public function setImage(string $image)
    {
        $this->setData(self::IMAGE_FIELD_NAME, $image);
    }




    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->getData(self::ID_FIELD_NAME);
    }



    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME_FIELD_NAME);
    }


    /**
     * @return int
     */
    public function getQty(): ?int
    {
        return $this->getData(self::QTY_FIELD_NAME);
    }

    /**
     * @return float
     */
    public function getPrice(): ?float
    {
        return $this->getData(self::PRICE_FIELD_NAME);
    }


    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->getData(self::IMAGE_FIELD_NAME);
    }

    /**
     * @param array $rawData
     * @throws \Macopedia\Allegro\Model\Api\ClientException
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['id'])) {
            $this->setId($rawData['id']);
        }
        if (isset($rawData['name'])) {
            $this->setName($rawData['name']);
        }
        if (isset($rawData['stock']['available'])) {
            $this->setQty($rawData['stock']['available']);
        }
        if (isset($rawData['sellingMode']['price']['amount'])) {
            $this->setPrice($rawData['sellingMode']['price']['amount']);
        }
        if($rawData['images']){
            $this->setImage($rawData['images'][0]['url'] ?? '');
        }

    }

}
