<?php

namespace IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\tests\_support\Service\Interface;

interface InterfaceModelParserTest
{
    /**
     * @param $inputFilePath
     * @return mixed
     */
    public function getFullList($inputFilePath);
}