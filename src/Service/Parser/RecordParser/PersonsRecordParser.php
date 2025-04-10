<?php

namespace IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\RecordParser;

use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Exception\ParserException;
use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\AttributeParser\PersonsAttributeParser\InsertBracketAttributeParser;
use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\AttributeParser\PersonsAttributeParser\InsertBracketExplodAttributeParser;
use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Service\Parser\RecordParser\Interface\RecordParserInterface;

class PersonsRecordParser implements RecordParserInterface
{
    public static function getList($personsRaw): array
    {
        $Persons = trim($personsRaw, '"');
        // Извлекаем данные с использованием регулярного выражения
        preg_match_all('/(\d+)\. (.+?)(?=(\d+)\. |$)/s', $Persons, $matches, PREG_SET_ORDER);

        $result = [];
        // Извлекаем результаты из регулярных выражений и обрезаем лишние пробелы
        foreach ($matches as $match) {
            $result[] = trim($match[0]);
        }

        $clean = [];
        foreach ($result as $item) {
            $parts = explode('.', $item, 2);
            $name = '';
            if (count($parts) > 1) {
                // Берем часть после точки и удаляем лишние пробелы
                $afterDot = trim($parts[1]);

                // Разделяем по первой запятой
                $namePart = explode(',', $afterDot, 2)[0];

                // Удаляем символ '*' из имени
                $namePart = str_replace('*', '', $namePart);
                $name = $namePart;
            }
            $temp = [
                [
                    "name"  => "profile_type",
                    "value" => "individual",
                ],
                [
                    "name"  => "profile_original_name",
                    "value" => $name,
                ],
                [
                    "name"  => "profile_first_name",
                    "value" => explode(' ', $name)[1],
                ],
                [
                    "name"  => "profile_second_name",
                    "value" => explode(' ', $name)[0],
                ],
            ];
            if (isset(explode(' ', $name)[2])) {
                $temp[] = [
                    "name"  => "profile_third_name",
                    "value" => explode(' ', $name)[2] ?? '',
                ];
            }
            if (preg_match('/(\d{2}\.\d{2}\.\d{4})\s+г\.р\./', $afterDot, $dateMatches)) {
                $birthDate = $dateMatches[1]; // Дата рождения
                $temp[] = [
                    "name"  => "profile_birth_date",
                    "value" => $birthDate,
                ];
            }
            if (preg_match('/г\.р\.\s*,\s*(.*?);/', $afterDot, $locationMatches)) {
                $location = trim($locationMatches[1]); // Место
                $temp[] = [
                    "name"  => "profile_birth_place",
                    "value" => $location,
                ];
            }
            $clean[] = $temp;
            // проверка на содержимое в ()
            if(strpos($item , '(') && preg_match_all('/\;/', $item) === 1) {
                $clean[] = InsertBracketAttributeParser::getData($item, $afterDot);
            }
            // проверка на содержимое в () и внутри скобок есть разбиение через ;
            if(strpos($item , '(') && preg_match_all('/\;/', $item) > 1) {
                preg_match('/\(([^)]+)\)/', $item, $matches);
                $names = explode(';' , $matches[1]);
                foreach ($names as $name) {
                    $clean[] = InsertBracketExplodAttributeParser::getData($name, $afterDot);
                }
            }
        }
        return !empty($clean)
            ? $clean
            : throw new ParserException('Выходной формат не может быть пустым !');
    }
}