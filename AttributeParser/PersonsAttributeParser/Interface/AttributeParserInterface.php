<?php

namespace IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\AttributeParser\PersonsAttributeParser\Interface;

interface AttributeParserInterface
{
    /**
     * Метод получения массива из обработанной строки
     *
     * @param $item
     * @param $afterDot
     * @return mixed
     */
    public static function getData($item, $afterDot);
}