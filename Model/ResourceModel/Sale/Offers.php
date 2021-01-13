<?php

namespace Macopedia\Allegro\Model\ResourceModel\Sale;

use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\Api\Client;
use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\Api\TokenProvider;
use Macopedia\Allegro\Model\Guid;
use Macopedia\Allegro\Model\ResourceModel\AbstractResource;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Resource model to get offers from Allegro API
 */
class Offers extends AbstractResource
{
    /** @var Guid */
    protected $guid;

    const limit = 100;

    /**
     * Offers constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Client $client
     * @param TokenProvider $tokenProvider
     * @param Logger $logger
     * @param Guid $guid
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Client $client,
        TokenProvider $tokenProvider,
        Logger $logger,
        Guid $guid
    ) {
        parent::__construct($scopeConfig, $client, $tokenProvider, $logger);
        $this->guid = $guid;
    }

    /**
     * @param string $offerId
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function get(string $offerId)
    {
        return $this->requestGet('/sale/offers/' . $offerId);
    }

    public function all(int $page = 0)
    {
        return $this->requestGet(sprintf('/sale/offers?limit=%d&offset=%d', self::limit, $this->getOffset($page)));
    }


    /**
     * @param string $offerId
     * @param array $params
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function putOffer(string $offerId, array $params)
    {
        return $this->requestPut('/sale/offers/' . $offerId, $params);
    }

    /**
     * @param array $params
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function postOffer(array $params)
    {
        return $this->requestPost('/sale/offers', $params);
    }

    /**
     * @param array $params
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function changeOfferStatus(array $params)
    {
        $commandId = $this->guid->getGuid();
        return $this->requestPut('/sale/offer-publication-commands/' . $commandId, $params);
    }

    /**
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getOfferStatus()
    {
        return $this->requestGet('/sale/offer-publication-commands/' . $this->guid->getGuid());
    }

    /**
     * @param string $allegroOfferId
     * @param int $qty
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function changeQuantity(string $allegroOfferId, int $qty)
    {
        $params = [
            'modification'  => [
                'changeType' => 'FIXED',
                'value'      => $qty,
            ],
            'offerCriteria' => [
                [
                    'offers' => [
                        [
                            'id' => $allegroOfferId,
                        ],
                    ],
                    'type'   => 'CONTAINS_OFFERS',
                ],
            ],
        ];

        return $this->requestPut('/sale/offer-quantity-change-commands/' . $this->guid->getGuid(), $params);
    }

    /**
     * @param string $allegroOfferId
     * @param float $price
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function changePrice(string $allegroOfferId, float $price)
    {
        $params = [
            'modification'  => [
                'type' => 'FIXED_PRICE',
                'price' => [
                    'amount' => $price,
                    'currency' => 'PLN',
                ],
            ],
            'offerCriteria' => [
                [
                    'offers' => [
                        [
                            'id' => $allegroOfferId,
                        ],
                    ],
                    'type'   => 'CONTAINS_OFFERS',
                ],
            ],
        ];

        return $this->requestPut('/sale/offer-price-change-commands/' . $this->guid->getGuid(), $params);
    }

    public function getLimit()
    {
        return self::limit;
    }

    /**
     * @param int $page
     * @return float|int
     */
    protected function getOffset(int $page)
    {
        return ($page * $this->getLimit());
    }

    /**
     * @param string $phrase
     * @param int $offset
     * @param int $limit
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function competitions(string $phrase, $offset=0, int $limit = 60)
    {
        return $this->requestGet(
            sprintf(
                '/offers/listing?limit=%s&phrase=%s&sellingMode.format=BUY_NOW&offset=%s',
                $limit,
                $phrase,
                ($offset*$limit)
            )
        );
    }
}
