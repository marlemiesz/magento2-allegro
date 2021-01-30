<?php

namespace Macopedia\Allegro\Api;

use Macopedia\Allegro\Api\Data\CompetitionInterface;
use Macopedia\Allegro\Api\Data\CompetitionModelInterface;
use Macopedia\Allegro\Api\Data\ReservationInterface;
use Macopedia\Allegro\Model\Competition;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;

interface CompetitionRepositoryInterface
{
    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return ReservationInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array;

    /**
     * @param CompetitionInterface $competition
     * @return void
     * @throws CouldNotSaveException
     */
    public function save(CompetitionModelInterface $competition): void;

    /**
     * @param CompetitionInterface $competition
     * @return void
     * @throws CouldNotDeleteException
     */
    public function delete(CompetitionModelInterface $competition): void;

    /**
     * @param int $productId
     * @return void
     */
    public function deactivationByProductId(int $productId): void;

    /**
     * @param string $allegroAuctionId
     * @param int $productId
     * @return Competition
     */
    public function getAllegroAuctionId(string $allegroAuctionId, int $productId): Competition;

    /**
     * @return array
     */
    public function getNotChooseAllegroAuction(): array;

}
