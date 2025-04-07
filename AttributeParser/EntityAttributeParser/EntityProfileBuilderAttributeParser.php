<?php

namespace IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser;

use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser\Interface\AttributeParserInterface;

class EntityProfileBuilderAttributeParser implements AttributeParserInterface
{
    /**
     * Обработку строки с разделением через ; и наличием в ней символа *
     *
     * Пример входной строки: 2. NATIONAL SOCIALISM/WHITE POWER*  (NS/WP; NS/WP CREW; NS/WP GREW SPARROWS; SPARROWS CREW/WHITE POWER; НАЦИОНАЛ-СОЦИАЛИЗМ/БЕЛАЯ СИЛА, ВЛАСТЬ), ;
     * Пример выходных данных: NATIONAL SOCIALISM/WHITE POWER, NS/WP, NS/WP CREW, NS/WP GREW SPARROWS, SPARROWS CREW/WHITE POWER, НАЦИОНАЛ-СОЦИАЛИЗМ/БЕЛАЯ СИЛА, ВЛАСТЬ
     *
     * @param $string
     * @return array|void
     */
    public static function get($string)
    {
        $item = StringFormatterAttributeParser::ProcessReplaceString($string);
        $temp = [];
        if(preg_match('/\((.*?)\)/s', $item, $matches) && isset($matches[1]) && count(array_map('trim', explode(';', $matches[1]))) > 1) {
            $subSplitArray = array_map('trim', explode(';', $matches[1]));
            $mainPart = trim(explode('(', $item)[0]);

            $mainPartial = [
                [
                    "name" => "profile_type",
                    "value" => 'entity',
                ],
                [
                    "name" => "profile_original_name",
                    "value" => $mainPart,
                ],
                [
                    "name" => "profile_first_name",
                    "value" => $mainPart,
                ]
            ];
            if(!empty($mainPartial)) {
                $temp[] = $mainPartial;
            }
            foreach ($subSplitArray as $subSplit) {
                $entityProfile = [
                    [
                        "name" => "profile_type",
                        "value" => 'entity',
                    ],
                    [
                        "name" => "profile_original_name",
                        "value" => $subSplit,
                    ],
                    [
                        "name" => "profile_first_name",
                        "value" => $subSplit,
                    ]
                ];
                $temp[] = $entityProfile;
            }
        }
        if(!empty($temp)) {
            return $temp;
        }
    }
}