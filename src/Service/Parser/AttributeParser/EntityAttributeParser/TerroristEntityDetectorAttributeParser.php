<?php

namespace IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser;
use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Repository\ConfigRepository;
use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser\Interface\AttributeParserInterface;

class TerroristEntityDetectorAttributeParser implements AttributeParserInterface
{
    /**
     * Обработка террористов из зарезервированного конфига (частные случаи)
     *
     * @param $inputString
     * @param $namesTerrorists
     * @return array
     */
    public static function get($inputString)
    {
        $temp = [];

        foreach (ConfigRepository::get('terrorist_names') as $name) {
            $nameToCheck = trim(explode('~', $name)[0]);
            if (strpos($inputString, $nameToCheck) !== false) {
                $profile = [
                    [
                        "name" => "profile_type",
                        "value" => 'individual',
                    ],
                    [
                        "name" => "profile_original_name",
                        "value" => $nameToCheck,
                    ],
                    [
                        "name" => "profile_second_name",
                        "value" => explode(' ', $nameToCheck)[0],
                    ],
                    [
                        "name" => "profile_first_name",
                        "value" => explode(' ', $nameToCheck)[1],
                    ],
                    [
                        "name" => "profile_third_name",
                        "value" => isset(explode(' ', $nameToCheck)[2]) ? explode(' ', $nameToCheck)[2] : '',
                    ],
                    [
                        "name" => "profile_birth_date",
                        "value" => trim(explode('~', $name)[1]),
                    ],
                ];

                // Проверка наличия места рождения
                $parts = explode('~', $name);
                if (count($parts) > 2 && !empty(trim($parts[2])) && strpos($inputString, $parts[2]) !== false) {
                    $profile[] = [
                        "name" => "profile_birth_place",
                        "value" => trim($parts[2]),
                    ];
                }
                $temp[] = $profile; // Добавляем профиль в массив
            }
        }

        // Check for entity names
        foreach (ConfigRepository::get('terrorist_entities') as $entityName) {
            $nameToCheck = trim(explode('~', $entityName)[0]);
            if (strpos($inputString, $nameToCheck) !== false) {
                $entityProfile = [
                    [
                        "name"  => "profile_type",
                        "value" => 'entity',
                    ],
                    [
                        "name"  => "profile_original_name",
                        "value" => $nameToCheck,
                    ],
                    [
                        "name"  => "profile_first_name",
                        "value" => $nameToCheck,
                    ],
                    [
                        "name"  => "profile_birth_date",
                        "value" => trim(explode('~', $entityName)[1]),
                    ],
                ];

                // Add place of birth if available
                $parts = explode('~', $entityName);
                if (count($parts) > 2 && !empty(trim($parts[2]))) {
                    $entityProfile[] = [
                        "name"  => "profile_birth_place",
                        "value" => trim($parts[2]),
                    ];
                }
                if (count($parts) > 3 && !empty(trim($parts[3]))) {
                    $entityProfile[] = [
                        "name"  => "profile_inn",
                        "value" => trim($parts[3]),
                    ];
                }

                $temp[] = $entityProfile;
            }
        }
        return $temp; // Return combined results
    }
}