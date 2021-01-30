<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Data\CompetitionModelInterface;
use Magento\Framework\Model\AbstractModel;
use Macopedia\Allegro\Model\ResourceModel\Competition as ResourceModel;

class Competition extends AbstractModel implements CompetitionModelInterface
{
    /**#@+
     * Constants for columns names
     */
    const ENTITY_ID_FIELD = 'entity_id';
    const PRODUCT_ID_FIELD = 'product_id';
    const ALLEGRO_AUCTION_ID_FIELD = 'allegro_auction_id';
    const PRICE_FIELD = 'price';
    const QUANTITY_FIELD = 'quantity';
    const ACTIVE_FIELD = 'active';
    const IS_COMPETITION_FIELD = 'is_competition';
    const NAME_FIELD = 'name';
    const IMAGE_FIELD = 'image';
    const IS_CHOOSE_FIELD = 'is_choose';
    /**#@-*/

    /**
     *
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @inheritDoc
     */
    public function getEntityId(): int
    {
        return (int)$this->getData(self::ENTITY_ID_FIELD);
    }

    /**
     * @inheritDoc
     */
    public function getProductId(): int
    {
        return (int)$this->getData(self::PRODUCT_ID_FIELD);
    }

    /**
     * @param int $product_id
     */
    public function setProductId(int $product_id): void
    {
        $this->setData(self::PRODUCT_ID_FIELD, $product_id);
    }

    /**
     * @return int
     */
    public function getAllegroAuctionId(): string
    {
        return (string)$this->getData(self::ALLEGRO_AUCTION_ID_FIELD);
    }

    /**
     * @param string $allegro_auction_id
     */
    public function setAllegroAuctionId(string $allegro_auction_id)
    {
        $this->setData(self::ALLEGRO_AUCTION_ID_FIELD, $allegro_auction_id);
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return (float)$this->getData(self::PRICE_FIELD);
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price)
    {
       $this->setData(self::PRICE_FIELD, $price);
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return (int)$this->getData(self::QUANTITY_FIELD);
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity)
    {
        $this->setData(self::QUANTITY_FIELD, $quantity);
    }

    /**
     * @return int
     */
    public function getIsCompetition(): bool
    {
        return (bool)$this->getData(self::IS_COMPETITION_FIELD);
    }

    /**
     * @param bool $is_competition
     * @return bool
     */
    public function setIsCompetition(bool $is_competition): bool
    {
        $this->setData(self::IS_COMPETITION_FIELD, (int) $is_competition);
    }


    /**
     * @return bool
     */
    public function getActive(): bool
    {
        return (bool)$this->getData(self::ACTIVE_FIELD);
    }

    /**
     * @param bool $active
     * @return bool
     */
    public function setActive(bool $active)
    {
        $this->setData(self::ACTIVE_FIELD, $active);
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->setData(self::NAME_FIELD, $name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (string)$this->getData(self::NAME_FIELD);
    }

    /**
     * @param string $image
     */
    public function setImage(?string $image)
    {
        $this->setData(self::IMAGE_FIELD, $image);
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return (string)$this->getData(self::IMAGE_FIELD);
    }

    /**
     * @param bool $is_choose
     */
    public function setIsChoose(bool $is_choose)
    {
        $this->setData(self::IS_CHOOSE_FIELD, $is_choose);
    }

    public function getIsChoose()
    {
        return (string)$this->getData(self::IS_CHOOSE_FIELD);
    }




}
