<?php

namespace IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser;
use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser\Interface\AttributeParserInterface;

class CreateProfileCollectionAttributeParser implements AttributeParserInterface
{
    /**
     * Формирования выходных данных в стандартной строки
     *
     * Пример использования входной строки: 1. FREE RUSSIA FOUNDATION , ;
     * Пример выходных данных: FREE RUSSIA FOUNDATION
     *
     * @param $values
     * @return array|void
     */
    public static function get($values)
    {
        $temp = [];
        foreach ($values as $value) {
            $entites = [
                [
                    "name"  => "profile_type",
                    "value" => 'entity',
                ],
                [
                    "name"  => "profile_original_name",
                    "value" => $value,
                ],
                [
                    "name"  => "profile_first_name",
                    "value" => $value,
                ]
            ];
            $temp[] = $entites;
        }
        if (!empty($temp)) {
            return $temp;
        }
    }
}