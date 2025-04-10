<?php

namespace IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser;

use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser\Interface\AttributeParserInterface;

class TerroristNameCheckerAttributeParser implements AttributeParserInterface
{
    /**
     * Получение распаршенных террористов из зарезервированного конфига (частные случаи)
     *
     * Пример входной строки
     * 703. ОБЪЕДИНЕНИЕ, ЧЛЕНАМИ КОТОРОГО ЯВЛЯЮТСЯ: ДРОБОТ ВЛАДИМИР ИВАНОВИЧ, РОДИВШИЙСЯ 06.07.1964, ДРОБОТ АНАТОЛИЙ ИВАНОВИЧ, РОДИВШИЙСЯ 29.04.1968 , ;
     *
     * @param $string
     * @param $namesTerrorists
     * @param $dot
     * @return array|void
     */
    public static function get($string)
    {
        preg_match('/\d+\.\s+(.*)/', $string, $matches);
        if (!empty($matches[1])) {
            $temp = TerroristEntityDetectorAttributeParser::get(trim($matches[1]));
            return $temp;
        }
    }
}