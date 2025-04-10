<?php

namespace IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\ModelParser;

use IncidentCenter\RL\CloudFunctions\MainFileHandlerParsers\Service\Interface\InterfaceModelParser;

use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\ListParser\RfmListParser;

class Parser implements InterfaceModelParser
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