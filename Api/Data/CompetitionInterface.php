<?php

namespace Macopedia\Allegro\Api\Data;

use Macopedia\Allegro\Api\Data\Offer\AfterSalesServicesInterface;
use Macopedia\Allegro\Api\Data\Offer\LocationInterface;

interface CompetitionInterface
{

    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id);

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name);

    /**
     * @param int $qty
     * @return void
     */
    public function setQty(int $qty);

    /**
     * @param float $price
     * @return void
     */
    public function setPrice(float $price);

    /**
     * @param string $image
     */
    public function setImage(string $image);

    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return string
     */
    public function getName(): ?string;

    /**
     * @return int
     */
    public function getQty(): ?int;

    /**
     * @return float
     */
    public function getPrice(): ?float;

    /**
     * @return string
     */
    public function getImage(): string;

    /**
     * @param array $rawData
     * @throws \Macopedia\Allegro\Model\Api\ClientException
     */
    public function setRawData(array $rawData);
}
