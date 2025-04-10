<?php

namespace IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\tests\_support\Service\Interface;

interface TestStorageInterface
{
    /**
     * Удалить файл в тестовом хранилище
     *
     * @param string $fileName
     * @return void
     */
    public function removeFile(string $fileName): void;
}