<?php

namespace IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\tests\_support\Service;

use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\tests\_support\Service\Interface\InterfaceModelParserTest;
use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\tests\_support\Service\Interface\StorageInterface;
use function Sentry\captureException;

class FileHandler
{
    private const TMP_FOLDER_PATH = '/tmp';

    public function __construct(
        private InterfaceModelParserTest $parser,
        private StorageInterface     $outputFileStorage,
        private StorageInterface     $inputFileStorage,
        private string               $inputFileName,
        private string               $outputFileName
    )
    {
    }


    /**
     * @return void
     * @throws \Exception
     */
    public function handle(): void
    {
        try {
            $tmpInputFilePath = self::TMP_FOLDER_PATH . '/' . $this->inputFileName;
            $tmpOutputFilePath = self::TMP_FOLDER_PATH . '/' . $this->outputFileName;

            echo "Скачивание файла \n";
            $this->saveTmpInput($tmpInputFilePath, $this->inputFileName);

            echo "Парсинг \n";
            $this->createTmpOutput($tmpInputFilePath, $tmpOutputFilePath);

            echo "Загрузка в хранилище \n";
            $this->saveToStorage($this->outputFileName, $tmpOutputFilePath);

        } catch (\Exception $exception) {
            captureException($exception);
            throw $exception;
        } finally {
            $this->cleanTmp($tmpInputFilePath, $tmpOutputFilePath);
        }
    }

    /**
     * @param string $tmpInputFilePath
     * @param string $inputFileName
     * @return void
     */
    private function saveTmpInput(string $tmpInputFilePath, string $inputFileName): void
    {
        file_put_contents(
            $tmpInputFilePath, $this->inputFileStorage->getFileContent($inputFileName)
        );
    }

    /**
     * @param string $tmpInputFilePath
     * @param string $tmpOutputFilePath
     * @return void
     */
    private function createTmpOutput(string $tmpInputFilePath, string $tmpOutputFilePath): void
    {
        $outputFile = fopen($tmpOutputFilePath, 'wb+');
        foreach ($this->parser->getFullList($tmpInputFilePath) as $record) {
            foreach ($record as $item) {
                $jsonLine = json_encode($item, JSON_UNESCAPED_UNICODE);
                fwrite($outputFile, $jsonLine . PHP_EOL);
            }
        }
        fclose($outputFile);
    }

    /**
     * @param string $outputFileName
     * @param string $tmpOutputFilePath
     * @return void
     */
    private function saveToStorage(string $outputFileName, string $tmpOutputFilePath): void
    {
        $this->outputFileStorage->putFile($outputFileName, $tmpOutputFilePath);
    }

    /**
     * @param string $tmpInputFilePath
     * @param string $tmpOutputFilePath
     * @return void
     */
    private function cleanTmp(string $tmpInputFilePath, string $tmpOutputFilePath): void
    {
        unlink($tmpInputFilePath);
        unlink($tmpOutputFilePath);
    }
}