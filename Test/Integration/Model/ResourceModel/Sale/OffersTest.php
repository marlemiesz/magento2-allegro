<?php


namespace Macopedia\Allegro\Test\Integration\Model\ResourceModel\Sale;


use Macopedia\Allegro\Model\ResourceModel\Sale\Offers;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;

class OffersTest extends TestCase
{
    /**
     * @var mixed
     */
    private $offers;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();

        $this->offers = $this->objectManager->get(Offers::class);
    }

    public function testCompetitions()
    {
        $offers = $this->offers->competitionsAll('samsung');
        var_dump($offers);die();
    }
}
