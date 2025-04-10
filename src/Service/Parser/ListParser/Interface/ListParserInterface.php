<?php

namespace IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\ListParser\Interface;

interface ListParserInterface
{
    /**
     * @param string $inputFilePath
     * @return mixed
     */
    public static function getData(string $inputFilePath);
}