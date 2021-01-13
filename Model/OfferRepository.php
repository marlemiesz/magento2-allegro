<?php

namespace Macopedia\Allegro\Model;

use Elasticsearch\Common\Exceptions\Curl\CouldNotConnectToHost;
use Macopedia\Allegro\Api\Data\ImageInterface;
use Macopedia\Allegro\Api\Data\OfferInterface;
use Macopedia\Allegro\Api\Data\OfferInterfaceFactory;
use Macopedia\Allegro\Api\Data\CompetitionInterfaceFactory;
use Macopedia\Allegro\Api\ImageRepositoryInterface;
use Macopedia\Allegro\Api\OfferRepositoryInterface;
use Macopedia\Allegro\Model\ResourceModel\Sale\Offers;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Macopedia\Allegro\Model\Api\ClientResponseException;
use Macopedia\Allegro\Model\Api\ClientException;
use Vertex\Exception\ApiException;

class OfferRepository implements OfferRepositoryInterface
{

    /** @var Offers */
    private $offers;

    /** @var OfferInterfaceFactory */
    private $offerFactory;

    /** @var ImageRepositoryInterface */
    private $imageRepository;
    /**
     * @var CompetitionInterfaceFactory
     */
    private $competitionInterfaceFactory;

    /**
     * OfferRepository constructor.
     * @param Offers $offers
     * @param OfferInterfaceFactory $offerFactory
     * @param ImageRepositoryInterface $imageRepository
     * @param CompetitionInterfaceFactory $competitionInterfaceFactory
     */
    public function __construct(
        Offers $offers,
        OfferInterfaceFactory $offerFactory,
        ImageRepositoryInterface $imageRepository,
        CompetitionInterfaceFactory $competitionInterfaceFactory
    ) {
        $this->offers = $offers;
        $this->offerFactory = $offerFactory;
        $this->imageRepository = $imageRepository;
        $this->competitionInterfaceFactory = $competitionInterfaceFactory;
    }

    /**
     * @param OfferInterface $offer
     * @throws ClientException
     * @throws CouldNotSaveException
     */
    public function save(OfferInterface $offer)
    {
        foreach ($offer->getImages() as $image) {
            if ($image->getStatus() == ImageInterface::STATUS_UPLOADED) {
                continue;
            }
            $this->imageRepository->save($image);
        }

        $offerRawData = $offer->getRawData();

        try {

            if ($offer->getId()) {
                $offerRawData = $this->offers->putOffer($offer->getId(), $offerRawData);
            } else {
                $offerRawData = $this->offers->postOffer($offerRawData);
            }

        } catch (ClientResponseException $e) {
            throw new CouldNotSaveException(__('Could not save offer Reason: %1', $e->getMessage()), $e);
        }

        if (!isset($offerRawData['id'])) {
            throw new CouldNotSaveException(__('Could not save offer'));
        }

        $offer->setRawData($offerRawData);
    }

    /**
     * @param string $offerId
     * @return OfferInterface
     * @throws ClientException
     * @throws NoSuchEntityException
     */
    public function get(string $offerId): OfferInterface
    {
        try {

            $offerData = $this->offers->get($offerId);

        } catch (ClientResponseException $e) {
            throw new NoSuchEntityException(__('Requested offer with id "%1" does not exist', $offerId), $e);
        }

        /** @var OfferInterface $offer */
        $offer = $this->offerFactory->create();
        $offer->setRawData($offerData);
        return $offer;
    }


    public function all(): array
    {
        $offers = [];
        $page = 0;
        try {
            do{
                $response = $this->offers->all($page++);
                foreach($response['offers'] as $offerData){
                    /** @var OfferInterface $offer */
                    $offer = $this->offerFactory->create();
                    $offer->setRawData($this->offers->get($offerData['id']));
                    $offers[] = $offer;

                }
            }while($this->isNextPage($response));


        } catch (ClientResponseException $e) {
            throw new CouldNotConnectToHost(__('Could not connect to host'), $e);
        }
        return $offers;

    }

    /**
     * @param string $phrase
     * @return array
     * @throws ClientException
     * @throws CouldNotConnectToHost
     */
    public function competitionAll(string $phrase):array
    {

        $page = 0;
        try {
            $competitions = [];
            do{
                $response = $this->offers->competitions($phrase, $page++);
                $competitions = array_merge($competitions, $this->setCompetition($response['items']['promoted']));
                $competitions = array_merge($competitions, $this->setCompetition($response['items']['regular']));

            }while($this->isNextPageCompetition($response));

            return $competitions;


        } catch (ClientResponseException $e) {
            throw new ApiException($e->getMessage());
        }
        return $offers;
    }

    /**
     * @param array $response
     * @return bool
     */
    protected function isNextPage(array $response): bool
    {

        return $response['count'] == $this->offers->getLimit() && $response['count'] < $response['totalCount'];
    }

    protected function isNextPageCompetition(array $response): bool
    {
        return (count($response['items']['promoted']) + count($response['items']['regular'])) > 0;
    }

    /**
     * @param array $offers
     * @return array
     */
    protected function setCompetition(array $offers): array
    {
        $competitions = [];
        foreach($offers as $offer) {
            $competition = $this->competitionInterfaceFactory->create();
            $competition->setRawData($offer);
            $competitions[] = $competition;
        }
        return $competitions;

    }
}
