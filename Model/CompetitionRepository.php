<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\CompetitionRepositoryInterface;
use Macopedia\Allegro\Api\Data\CompetitionModelInterface;
use Macopedia\Allegro\Api\Data\ReservationInterfaceFactory;
use Macopedia\Allegro\Model\ResourceModel\Reservation\CollectionFactory;
use Macopedia\Allegro\Model\ResourceModel\Reservation\Collection;
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
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);
        $collection->load();

        return $collection->getItems();
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

}
