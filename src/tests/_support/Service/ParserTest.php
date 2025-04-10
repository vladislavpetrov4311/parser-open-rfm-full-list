<?php

namespace IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\tests\_support\Service;

use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\tests\_support\Service\Interface\InterfaceModelParserTest;
use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\ListParser\RfmListParser;

class ParserTest implements InterfaceModelParserTest
{
    /**
     * Метод получения окончательного распаршенного списка
     *
     * @param $inputFilePath
     * @return array|mixed|null
     */
    public function getFullList($inputFilePath)
    {
        return RfmListParser::getData($inputFilePath);
    }
}