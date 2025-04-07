<?php

namespace IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\RecordParser;
use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Exception\ParserException;
use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser\EntityProfileBuilderAttributeParser;
use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser\InnAttributeParser;
use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser\InsertBracketAttributeParser;
use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser\StringFormatterAttributeParser;
use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\AttributeParser\EntityAttributeParser\TerroristNameCheckerAttributeParser;
use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\RecordParser\Interface\RecordParserInterface;

class EntityRecordParser implements RecordParserInterface
{
    /**
     * Формируем распаршенный список Организаций
     * @param $entitiesRaw
     * @return array|null
     */
    public static function getList($entitiesRaw)
    {
        // Удаляем лишние кавычки в начале и в конце
        $entitiesRaw = trim($entitiesRaw, '"');

        // Извлекаем данные с использованием регулярного выражения
        preg_match_all('/(\d+)\. (.+?)(?=(\d+)\. |$)/s', $entitiesRaw, $matches, PREG_SET_ORDER);

        $result = [];
        // Извлекаем результаты из регулярных выражений и обрезаем лишние пробелы
        foreach ($matches as $match) {
            $result[] = trim($match[0]);
        }

        $clean = [];
        foreach ($result as $item) {
            // Проверяем, является ли элемент объединением
            if (strpos($item, 'ОБЪЕДИНЕНИЕ, ЧЛЕНАМИ') !== false || strpos($item, 'ОБЪЕДИНЕНИЕ,ЧЛЕНАМИ') !== false) {
                $tempArray = TerroristNameCheckerAttributeParser::get($item);
                $clean = array_merge($clean, $tempArray); // Добавляем элементы в $clean
            } elseif (strpos($item, 'ИНН:') !== false) { // Проверяем наличие ИНН
                $arrayWithInn = InnAttributeParser::get($item);
                $clean[] = $arrayWithInn;
            } elseif (preg_match('/\*\s*\(([^()]*\([^()]*\)[^()]*)\)/', $item) && preg_match_all('/\(/', $item) >= 2) {
                // Проверяем наличие вложенных скобок с определенным количеством открывающих скобок
                $arrayWithClip = InsertBracketAttributeParser::get($item);
                $clean = array_merge($clean, $arrayWithClip); // Добавляем элементы в $clean
            } elseif (!empty(EntityProfileBuilderAttributeParser::get($item))) {
                // Проверяем наличие объектов внутри скобок через `;`
                $subSplits = EntityProfileBuilderAttributeParser::get($item);
                $clean = array_merge($clean, $subSplits); // Добавляем вложенные объекты
            } else {
                // Формируем объект в нужном формате
                $temp = [
                    [
                        "name"  => "profile_type",
                        "value" => 'entity',
                    ],
                    [
                        "name"  => "profile_original_name",
                        "value" => StringFormatterAttributeParser::ProcessReplaceString($item)
                    ],
                    [
                        "name"  => "profile_first_name",
                        "value" => StringFormatterAttributeParser::ProcessReplaceString($item)
                    ],
                ];
                $clean[] = $temp; // Добавляем конечный элемент в $clean
            }
        }
        return !empty($clean)
            ? $clean
            : throw new ParserException('Выходной формат не может быть пустым !');
    }
}