<?php

namespace IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\AttributeParser\PersonsAttributeParser;

use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\AttributeParser\PersonsAttributeParser\Interface\AttributeParserInterface;
class InsertBracketExplodAttributeParser implements AttributeParserInterface
{
    /**
     * Случай, если у нас есть скобки и в скобках имена разделены через ;
     *
     * ПРИМЕР: 83. АБДУКАРИМОВ МАГОМЕД МАГОМЕДОВИЧ*,  (АБДУКЕРИМОВ МАГОМЕД МАГОМЕДОВИЧ; АБДУЛКЕРИМОВ МАГОМЕД МАГОМЕДОВИЧ), 03.05.1964 г.р. , С. ЭЧЕДА ЦУМАДИНСКОГО РАЙОНА РЕСПУБЛИКИ ДАГЕСТАН;
     * На выходе получаем распаршенные ФИО: АБДУКАРИМОВ МАГОМЕД МАГОМЕДОВИЧ, АБДУКЕРИМОВ МАГОМЕД МАГОМЕДОВИЧ, АБДУЛКЕРИМОВ МАГОМЕД МАГОМЕДОВИЧ
     *
     * @param $name
     * @param $afterDot
     * @return array|void
     */
    public static function getData($name, $afterDot)
    {
                $temp3 = [
                    [
                        "name"  => "profile_type",
                        "value" => "individual",
                    ],
                    [
                        "name"  => "profile_original_name",
                        "value" => trim($name),
                    ],
                    [
                        "name"  => "profile_first_name",
                        "value" => explode(' ', trim($name))[1],
                    ],
                    [
                        "name"  => "profile_second_name",
                        "value" => explode(' ', trim($name))[0],
                    ],
                ];
                if (isset(explode(' ', trim($name))[2])) {
                    $temp3[] = [
                        "name"  => "profile_third_name",
                        "value" => explode(' ', trim($name))[2] ?? '',
                    ];
                }
                if (preg_match('/(\d{2}\.\d{2}\.\d{4})\s+г\.р\./', $afterDot, $dateMatches)) {
                    $birthDate = $dateMatches[1]; // Дата рождения
                    $temp3[] = [
                        "name"  => "profile_birth_date",
                        "value" => $birthDate,
                    ];
                }
                if (preg_match('/г\.р\.\s*,\s*(.*?);/', $afterDot, $locationMatches)) {
                    $location = trim($locationMatches[1]); // Место
                    $temp3[] = [
                        "name"  => "profile_birth_place",
                        "value" => $location,
                    ];
                }
                return $temp3;
            }
}