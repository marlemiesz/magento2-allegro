<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\CompetitionRepositoryInterface;
use Macopedia\Allegro\Api\Data\CompetitionModelInterface;
use Macopedia\Allegro\Api\Data\ReservationInterfaceFactory;
use Macopedia\Allegro\Model\ResourceModel\Collection\CollectionFactory;
use Macopedia\Allegro\Model\ResourceModel\Collection\Collection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Macopedia\Allegro\Model\ResourceModel\Collection as ResourceModel;

class CompetitionRepository implements CompetitionRepositoryInterface
{
    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var CollectionProcessorInterface */
    private $collectionProcessor;

    /** @var ResourceModel */
    private $resource;

    /** @var ReservationInterfaceFactory */
    private $reservationFactory;

    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /**
     * ReservationLogRepository constructor.
     * @param CollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ResourceModel $resource
     * @param ReservationInterfaceFactory $reservationFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        ResourceModel $resource,
        ReservationInterfaceFactory $reservationFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->resource = $resource;
        $this->reservationFactory = $reservationFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     */
    public function getCollection(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        return $collection->load();
    }

    /**
     * @inheritDoc
     */
    public function getOne(SearchCriteriaInterface $searchCriteria): Competition
    {
        return $this->getCollection($searchCriteria)->getFirstItem();
    }
    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array
    {

        return $this->getCollection($searchCriteria)->getItems();
    }

    /**
     * @inheritDoc
     */
    public function save(CompetitionModelInterface $competition): void
    {
        try {
            $this->resource->save($competition);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__(
                'Could not save competition with id %1',
                $competition->getId()
            ), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function delete(CompetitionModelInterface $competition): void
    {
        try {
            $this->resource->delete($competition);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__(
                'Could not delete competition with id %1',
                $competition->getId()
            ), $e);
        }
    }


    /**
     * @param int $productId
     * @return void
     */
    public function deactivationByProductId(int $productId): void
    {

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(Competition::PRODUCT_ID_FIELD, $productId)
            ->create();

        $collection = $this->getCollection($searchCriteria);

        $collection->setDataToAll(Competition::ACTIVE_FIELD, false);
        $collection->save();
    }

    /**
     * @param string $allegroAuctionId
     * @return array
     */
    public function getAllegroAuctionId(string $allegroAuctionId): Competition
    {

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(Competition::ALLEGRO_AUCTION_ID_FIELD, $allegroAuctionId)
            ->create();
        return $this->getOne($searchCriteria);
    }

}
