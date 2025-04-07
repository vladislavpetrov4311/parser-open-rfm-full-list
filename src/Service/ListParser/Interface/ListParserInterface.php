<?php

namespace IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\ListParser\Interface;

interface ListParserInterface
{
    /**
     * @param string $inputFilePath
     * @return mixed
     */
    public static function getData(string $inputFilePath);
}