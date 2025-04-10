<?php

namespace IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\RecordParser\Interface;

interface RecordParserInterface
{
    /**
     * Метод для получения распаршенного списка
     *
     * @param $RawData
     * @return mixed
     */
    public static function getList($RawData);
}
