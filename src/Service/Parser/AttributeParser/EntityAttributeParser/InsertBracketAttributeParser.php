<?php

namespace IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser;

use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser\Interface\AttributeParserInterface;

class InsertBracketAttributeParser implements AttributeParserInterface
{
    /**
     * Обработка случая, когда есть вложенные скобки и внутри разбиение через ; либо нет разбиения
     *
     * Пример входной строки разбиения через ; : 143. ДВИЖЕНИЕ ФЕДЕРАЦИЯ ДОНА И ПОВОЛЖЬЯ*  (КОН(ФЕДЕРАЦИЯ) ПОВОЛЖЬЕ; ПОВОЛЖСКАЯ ФЕДЕРАЦИЯ), ;","
     * Пример выходных данных: ДВИЖЕНИЕ ФЕДЕРАЦИЯ ДОНА И ПОВОЛЖЬЯ, КОН(ФЕДЕРАЦИЯ) ПОВОЛЖЬЕ, ПОВОЛЖСКАЯ ФЕДЕРАЦИЯ
     *
     * Пример входной строки без разбиения через ; : 151. ДЖЕБХАТ АН-НУСРА (ФРОНТ ПОБЕДЫ)*  (ДЖИБХА АЛЬ-НУСРА ЛИ АХЛЬ АШ-ШАМ (ФРОНТ ПОДДЕРЖКИ ВЕЛИКОЙ СИРИИ)), ;","
     * Пример выходных данных: ДЖЕБХАТ АН-НУСРА (ФРОНТ ПОБЕДЫ), ДЖИБХА АЛЬ-НУСРА ЛИ АХЛЬ АШ-ШАМ (ФРОНТ ПОДДЕРЖКИ ВЕЛИКОЙ СИРИИ)
     *
     * @param $item
     * @return array|void|null
     */
    public static function get($item)
    {
        // Проверяем, если у нас в вложенных скобках нет разбиения через ;
        if(substr_count($item, ';') === 1) {
            $input_string = preg_replace('/^\d+\.\s*/', '', $item);
            $input_string = rtrim($input_string, ', ;');

            $parts = explode('*', $input_string);

            $result = array_map('trim', $parts);

            if (isset($result[1])) {
                $last_parenthesis_pos = strrpos($result[1], ')');

                if ($last_parenthesis_pos !== false) {
                    $result[1] = substr($result[1], 0, $last_parenthesis_pos + 1);
                }
                if(($result[1])[0] === '(' && ($result[1])[strlen($result[1]) - 1] === ')') {
                    $result[1] = substr($result[1], 1, -1);
                }
            }
            return CreateProfileCollectionAttributeParser::get($result);
        } else {
            $organization = trim(substr($item, 0, strpos($item, '*')));
            $organization = preg_replace('/^\d+\.\s*/', '', $organization);
            if (preg_match('/\(\s*(.*?)\)/', $item, $matches)) {
                $start = strpos($item, '(');
                $end = $start;
                $bracketCount = 0;

                while ($end < strlen($item)) {
                    if ($item[$end] == '(') {
                        $bracketCount++;
                    } elseif ($item[$end] == ')') {
                        if ($bracketCount > 0) {
                            $bracketCount--;
                        }
                        if ($bracketCount == 0) {
                            break;
                        }
                    }
                    $end++;
                }

                $external_value = trim(substr($item, $start + 1, $end - $start - 1));

                $values = explode(';', $external_value);

                $values = array_map('trim', $values);
                array_unshift($values, $organization);

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
    }
}