<?php

namespace IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\ListParser;

use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\ListParser\Interface\ListParserInterface;
use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\RecordParser\EntityRecordParser;
use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\RecordParser\PersonsRecordParser;

class RfmListParser implements ListParserInterface
{
    /**
     * Формируем список из парсинга Entity и Persons
     *
     * @param string $inputFilePath
     * @return array|mixed|null
     */
    public static function getData(string $inputFilePath)
    {
        $fileContents = file_get_contents($inputFilePath);
        $Data = explode('][', trim($fileContents, '[]'));
        $persons = $Data[1];
        $entitiesRaw = $Data[0];

        $merge = [EntityRecordParser::getList($entitiesRaw) , PersonsRecordParser::getList($persons)];
        return $merge;
    }
}