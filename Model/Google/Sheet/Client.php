<?php


namespace Macopedia\Allegro\Model\Google\Sheet;


use Google_Client;
use Google_Service_Sheets;

class Client
{
    /**
     * @var Google_Service_Sheets
     */
    private $service;


    /**
     * Client constructor.
     * @param string $configJson
     * @throws \Google\Exception
     */
    public function __construct(string $configJson)
    {

        $client = new Google_Client();

        $client->setApplicationName('Write Allegro competitions');

        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);

        $client->setAccessType('offline');
        $client->setAuthConfig(json_decode($configJson, true));
        $this->service = new Google_Service_Sheets($client);
    }

    /**
     * @return Google_Service_Sheets
     */
    public function getService(): Google_Service_Sheets
    {
        return $this->service;
    }


}
