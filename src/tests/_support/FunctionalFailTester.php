<?php

use Codeception\Scenario;
use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\FileHandler;
use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\Parser\ModelParser\Parser;
use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Service\StorageInterface;
use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\tests\_support\Service\TestFileStorage;
use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\tests\_support\Service\TestStorageInterface;
use IncidentCenter\RL\CloudFunctions\ParserOpenRfmFullList\Exception\ParserException;


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class FunctionalFailTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;
    use \Codeception\Util\Shared\Asserts;

    private StorageInterface $inputStorage;
    private StorageInterface|TestStorageInterface $outputStorage;
    private string $lastExceptionMessage = '';
    private string $expectedErrorMessage = '';

    public function __construct(Scenario $scenario)
    {
        $this->expectedErrorMessage = 'Выходной формат не может быть пустым !';
        $this->inputStorage = new TestFileStorage(__DIR__ . '/../_data');
        $this->outputStorage = new TestFileStorage(__DIR__ . '/../_output');
        parent::__construct($scenario);
    }

    /**
     * @When Я обрабатываю провальный файл
     */
    public function iProcessFile()
    {
        try {
            $fileHandler = new FileHandler(
                parser: new Parser(),
                outputFileStorage: $this->outputStorage,
                inputFileStorage: $this->inputStorage,
                inputFileName: $this->getInputFailFileName(),
                outputFileName: $this->getOutputFileName()
            );

            $fileHandler->handle();
        } catch (ParserException $e) {
            $this->lastExceptionMessage = $e->getExceptionMessage();
        }
    }

    /**
     * @Then Вижу, что выбрасывается ошибка
     */
    public function iSeeRightFile()
    {
        $this->assertStringContainsString(
            $this->expectedErrorMessage,
            $this->lastExceptionMessage,
            "Не удалось отловить ошибку об выходном пустом формате данных !"
        );
    }

    private function getInputFailFileName(): string
    {
        return 'inputFail.txt';
    }

    private function getOutputFileName(): string
    {
        return 'real-output.jsonl';
    }
}
