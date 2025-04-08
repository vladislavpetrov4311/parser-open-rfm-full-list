<?php

namespace IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\tests\_support\Service;

use IncidentCenter\RL\CloudFunctions\MainFileHandlerParsers\Service\StorageInterface;

class TestFileStorage implements StorageInterface, TestStorageInterface
{
    /**
     * @param string $storagePath
     */
    public function __construct(private string $storagePath) {}

    /**
     * @inheritdoc
     */
    public function putFile(string $fileName, string $filePath): void
    {
        $data = file_get_contents($filePath);
        file_put_contents($this->storagePath . '/' . $fileName, $data);
    }

    /**
     * @inheritdoc
     */
    public function getFileContent(string $fileName): string
    {
        return file_get_contents($this->storagePath . '/' . $fileName);
    }

    /**
     * @inheritdoc
     */
    public function removeFile(string $fileName): void
    {
        unlink($this->storagePath . '/' . $fileName);
    }
}