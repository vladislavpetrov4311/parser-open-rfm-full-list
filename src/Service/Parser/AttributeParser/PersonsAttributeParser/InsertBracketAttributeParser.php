<?php

namespace IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\AttributeParser\PersonsAttributeParser;

use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\AttributeParser\PersonsAttributeParser\Interface\AttributeParserInterface;
class InsertBracketAttributeParser implements AttributeParserInterface
{
    /**
     * Случай, когда у нас в скобках есть второе имя без разделителя ;
     *
     * ПРИМЕР: 15. АБАКАРОВ ЗАЛИМХАН ПАХРУДИНОВИЧ*,  (АБАКАРОВ ЗЕЛИМХАН ПАХРУДИНОВИЧ), 26.04.1981 г.р. , Г. ХАСАВЮРТ ДАССР;
     * На выходе получаем: АБАКАРОВ ЗАЛИМХАН ПАХРУДИНОВИЧ, АБАКАРОВ ЗЕЛИМХАН ПАХРУДИНОВИЧ
     *
     * @param $item
     * @param $afterDot
     * @return array|void
     */
    public static function getData($item, $afterDot)
    {
            preg_match('/\(([^)]+)\)/', $item, $matches);
            if (isset($matches[1]) && count(explode(' ', $matches[1])) > 1) {
                $temp2 = [
                    [
                        "name"  => "profile_type",
                        "value" => "individual",
                    ],
                    [
                        "name"  => "profile_original_name",
                        "value" => $matches[1],
                    ],
                    [
                        "name"  => "profile_first_name",
                        "value" => explode(' ', $matches[1])[1],
                    ],
                    [
                        "name"  => "profile_second_name",
                        "value" => explode(' ', $matches[1])[0],
                    ],
                ];
                if (isset(explode(' ', $matches[1])[2])) {
                    $temp2[] = [
                        "name"  => "profile_third_name",
                        "value" => explode(' ', $matches[1])[2] ?? '',
                    ];
                }
                if (preg_match('/(\d{2}\.\d{2}\.\d{4})\s+г\.р\./', $afterDot, $dateMatches)) {
                    $birthDate = $dateMatches[1]; // Дата рождения
                    $temp2[] = [
                        "name"  => "profile_birth_date",
                        "value" => $birthDate,
                    ];
                }
                if (preg_match('/г\.р\.\s*,\s*(.*?);/', $afterDot, $locationMatches)) {
                    $location = trim($locationMatches[1]); // Место
                    $temp2[] = [
                        "name"  => "profile_birth_place",
                        "value" => $location,
                    ];
                }
                return $temp2;
            }
        }
}