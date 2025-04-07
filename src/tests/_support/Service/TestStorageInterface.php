<?php

namespace IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\tests\_support\Service;

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