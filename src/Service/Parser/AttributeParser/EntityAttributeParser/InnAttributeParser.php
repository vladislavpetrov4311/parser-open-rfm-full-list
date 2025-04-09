<?php

namespace IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser;
use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser\Interface\AttributeParserInterface;

class InnAttributeParser implements AttributeParserInterface
{
    /**
     * Обработка случая с ИНН в строке
     *
     * Пример входной строки: 4. АВТОНОМНАЯ НЕКОММЕРЧЕСКАЯ ОРГАНИЗАЦИЯ БЛАГОТВОРИТЕЛЬНЫЙ ПАНСИОНАТ АК УМУТ- СВЕТЛАЯ НАДЕЖДА , , ИНН: 1653019714, ОГРН: 1021603062150;
     * Пример выходного формата: АВТОНОМНАЯ НЕКОММЕРЧЕСКАЯ ОРГАНИЗАЦИЯ БЛАГОТВОРИТЕЛЬНЫЙ ПАНСИОНАТ АК УМУТ- СВЕТЛАЯ НАДЕЖДА, 1653019714
     *
     * @param $item
     * @return array|void
     */
    public static function get($item)
    {
        if (preg_match('/\d+\.\s*(.*?)\s*,\s*,\s*ИНН:\s*(\d+),\s*ОГРН:\s*(\d+)/', $item, $matches)) {
            $temp = [
                [
                    "name"  => "profile_type",
                    "value" => 'entity',
                ],
                [
                    "name"  => "profile_original_name",
                    "value" => trim($matches[1]),
                ],
                [
                    "name"  => "profile_first_name",
                    "value" => trim($matches[1]),
                ],
                [
                    "name"  => "profile_inn",
                    "value" => trim($matches[2]),
                ]
            ];
        }
        if (!empty($temp)) {
            return $temp;
        }
    }
}