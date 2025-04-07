<?php

namespace IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\ModelParser\Interface;

interface InterfaceModelParser
{
    /**
     * @param $inputFilePath
     * @return mixed
     */
    public function getFullList($inputFilePath);
}