<?php


namespace Macopedia\Allegro\Model\Google;


use Google_Service_Sheets_ValueRange;
use Macopedia\Allegro\Model\Google\Sheet\Client;
use Macopedia\Allegro\Model\Google\Sheet\Collection\CompetitionCollection;

class Sheet
{
    /**
     * @var string
     */
    private $sheet_id;
    /**
     * @var string
     */
    private $sheet_name;
    /**
     * @var mixed
     */
    private $client;

    /**
     * Sheet constructor.
     * @param string $configJson
     * @param string $sheet_id
     * @param string $sheet_name
     * @throws \Google\Exception
     */
    public function __construct(string $configJson, string $sheet_id, string $sheet_name)
    {

        $this->sheet_id = $sheet_id;
        $this->sheet_name = $sheet_name;
        $this->client = (new Client($configJson))->getService();
    }

    /**
     * @param string $range
     * @return mixed
     */
    public function getData(string $range)
    {
        $response = $this->client->spreadsheets_values->get($this->sheet_id, $this->getRange($range));

        return $response->getValues() ?? [];
    }

    /**
     * @param string $range
     * @return string
     */
    protected function getRange(string $range): string
    {
        return sprintf("%s!%s", $this->sheet_name, $range);
    }

    public function clear(int $length, string $range)
    {
        $data = [];
        for($i=0;$i<=$length;$i++){
            $data[] = [
                '','','','','','','','','','','','','','',''
            ];
        }
        $body = new Google_Service_Sheets_ValueRange([

            'values' => $data

        ]);
        $params = [
            'valueInputOption' => 'USER_ENTERED'
        ];

        $this->client->spreadsheets_values->update($this->sheet_id, $this->getRange($range), $body, $params);
    }



    /**
     * @param CompetitionCollection $collections
     * @param string $range
     */
    public function setCompetitions(CompetitionCollection $collections, string $range)
    {
        $body = new Google_Service_Sheets_ValueRange([

            'values' => $collections->toGoogleSheetFormat()

        ]);
        $params = [
            'valueInputOption' => 'USER_ENTERED'
        ];

        $this->client->spreadsheets_values->update($this->sheet_id, $this->getRange($range), $body, $params);

    }

}
