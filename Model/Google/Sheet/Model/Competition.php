<?php


namespace Macopedia\Allegro\Model\Google\Sheet\Model;



class Competition
{
    /**
     * @var int|null
     */
    private $product_id;
    /**
     * @var string|null
     */
    private $auction_id;
    /**
     * @var string|null
     */
    private $auction_image;
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var float|null
     */
    private $price;
    /**
     * @var int|null
     */
    private $quantity;
    /**
     * @var string|null
     */
    private $status;
    /**
     * @var string|null
     */
    private $product_image;

    /**
     * @return int|null
     */
    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    /**
     * @param int|null $product_id
     */
    public function setProductId(?int $product_id): void
    {
        $this->product_id = $product_id;
    }

    /**
     * @return string|null
     */
    public function getAuctionId(): ?string
    {
        return $this->auction_id;
    }

    /**
     * @param string|null $auction_id
     */
    public function setAuctionId(?string $auction_id): void
    {
        $this->auction_id = $auction_id;
    }

    /**
     * @return string|null
     */
    public function getAuctionImage(): ?string
    {
        return $this->auction_image;
    }

    /**
     * @param string|null $auction_image
     */
    public function setAuctionImage(?string $auction_image): void
    {
        $this->auction_image = $auction_image;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     */
    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int|null $quantity
     */
    public function setQuantity(?int $quantity): void
    {
        $this->quantity = $quantity;
    }


    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }


    /**
     * Competition constructor.
     * @param int|null $product_id
     * @param string|null $auction_id
     * @param string|null $auction_image
     * @param string|null $name
     * @param float|null $price
     * @param int|null $quantity
     * @param string|null $product_image
     * @param string|null $status
     */
    public function __construct(
        ?int $product_id = null,
        ?string $auction_id = null,
        ?string $auction_image = null,
        ?string $name = null,
        ?float $price = null,
        ?int $quantity = null,
        ?string $product_image = null,
        ?string $status = null
    )
    {

        $this->product_id = $product_id;
        $this->auction_id = $auction_id;
        $this->auction_image = $auction_image;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->status = $status;
        $this->product_image = $product_image;
    }

    public function setRaw(array $data)
    {
        $this->setProductId((int)$data[0]);
        $this->setAuctionId($data[1]);
        $this->setName($data[3]);
        $this->setPrice($this->parsePriceToFloat($data[4]));
        $this->setQuantity((int) $data[5]);
        if(isset($data[8]) && $data[8]){
            $this->setStatus($data[8]);
        }

    }

    /**
     * @param $data
     * @return float
     */
    protected function parsePriceToFloat($data): float
    {
        return (float)str_replace([',', ' ', 'zł'], ['.', '', ''], $data);
    }

    /**
     * @return string|null
     */
    public function getProductImage(): ?string
    {
        return $this->product_image;
    }

    /**
     * @param string|null $product_image
     */
    public function setProductImage(?string $product_image): void
    {
        $this->product_image = $product_image;
    }


}
