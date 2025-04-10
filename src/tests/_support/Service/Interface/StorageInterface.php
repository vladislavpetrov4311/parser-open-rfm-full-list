<?php

namespace IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\tests\_support\Service\Interface;

interface StorageInterface
{
    /**
     * Положить файл в хранилище
     *
     * @param string $fileName
     * @param string $filePath
     * @return void
     */
    public function putFile(string $fileName, string $filePath): void;

    /**
     * Получить содержимое файла
     *
     * @param string $fileName
     * @return string
     */
    public function getFileContent(string $fileName): string;
}