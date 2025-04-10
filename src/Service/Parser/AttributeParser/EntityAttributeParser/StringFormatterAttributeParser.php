<?php

namespace IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser;
class StringFormatterAttributeParser
{
    /**
     * Обработка входной строки в 'чистый' формат
     * Пример входной строки: 1. FREE RUSSIA FOUNDATION , ;
     * Пример выходной строки: FREE RUSSIA FOUNDATION
     *
     * @param $string
     * @return string|void
     */
    public static function ProcessReplaceString($string)
    {
        $pattern = '/(.*?)\*\s*\(/'; //проверяем * и что после неё есть () НАПРИМЕР DATA_ID = 2 ВАРИАНТ ИЗ ВХОДНОГО СПИСКА
        $replacement = '$1 (';
        $res = preg_replace($pattern, $replacement, $string);;
        if($res !== $string) {
            if (preg_match('/\d+\.(.*?)(\(.+?\))/', $res, $matches)) {
                $result = trim($matches[1]) . ' ' . trim($matches[2]);
                return $result;
            }
        }
        if (preg_match('/\d+\.\s+(.*?)(?:\*|,)/', $string, $matches)) {
            $result = trim($matches[1]);
            return $result;
        }
    }
}