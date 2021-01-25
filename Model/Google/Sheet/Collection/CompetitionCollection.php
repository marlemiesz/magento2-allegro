<?php


namespace Macopedia\Allegro\Model\Google\Sheet\Collection;


use Macopedia\Allegro\Model\Google\Sheet\Model\Competition;

class CompetitionCollection
{
    private $items = [];

    /**
     * @param Competition $competition
     */
    public function add(Competition $competition)
    {
        $this->items[$competition->getProductId()][$competition->getAuctionId()] = $competition;
    }

    /**
     * @param Competition $competition
     */
    public function remove(Competition $competition){
        unset($this->items[$competition->getProductId()][$competition->getAuctionId()]);
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getItemsWithStatus(): array
    {
        $data = [];

        foreach ($this->items as $items)
        {
            foreach($items as $item){
                if($item->getStatus())
                {
                    $data[] = $item;
                }
            }

        }

        return $data;
    }

    public function toGoogleSheetFormat(): array
    {
        $data = [];
        foreach($this->items as $groupProducts){
            foreach ($groupProducts as $competition)
            {
                $data[] = [
                    $competition->getProductId(),
                    $competition->getAuctionId(),
                    $this->prepareImage($competition->getAuctionImage()),
                    $competition->getName(),
                    $competition->getPrice(),
                    $competition->getQuantity(),
                    $this->prepareAuctionUrl($competition->getAuctionId()),
                    $this->prepareImage($competition->getProductImage()),
                    ''
                ];
            }
        }
        return $data;
    }

    /**
     * @param string|null $image
     * @return string
     */
    protected function prepareImage(?string $image): string
    {
        return sprintf('=image("%s")', $image);
    }

    /**
     * @param string $auctionId
     * @return string
     */
    protected function prepareAuctionUrl(string $auctionId):string
    {
        return sprintf('http://allegro.pl/item%s.html', $auctionId);
    }

    public function count(): int
    {
        $i=0;
        foreach($this->items as $groupProducts) {
            foreach ($groupProducts as $competition) {
                $i++;
            }
        }
        return $i;
    }


}
