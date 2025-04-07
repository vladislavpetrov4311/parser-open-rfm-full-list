<?php

namespace IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser\Interface;

interface AttributeParserInterface
{
    /**
     * Метод получения массива из обработанной строки
     * @param $item
     * @return mixed
     */
    public static function get($item);
}