<?php


namespace Macopedia\Allegro\Model\CompetitionImport;


class SearchKeywordDecoder
{

    const delimiter = '|';


    /**
     * @param string $values
     * @return array
     */
    public function decode(string $values): array
    {
        return explode(self::delimiter, $values);
    }
}
