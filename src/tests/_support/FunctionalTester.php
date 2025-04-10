<?php

use Codeception\Scenario;

use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\tests\_support\Service\FileHandler;
use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\tests\_support\Service\ParserTest;
use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\tests\_support\Service\TestFileStorage;
use IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\tests\_support\Service\Interface\TestStorageInterface;


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
class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;
    use \Codeception\Util\Shared\Asserts;

    private TestStorageInterface $inputStorage;
    private TestStorageInterface $outputStorage;

    public function __construct(Scenario $scenario)
    {
        $this->inputStorage = new TestFileStorage(__DIR__ . '/../_data');
        $this->outputStorage = new TestFileStorage(__DIR__ . '/../_output');
        parent::__construct($scenario);
    }

    /**
     * @When Я обрабатываю файл
     */
    public function iProcessFile()
    {
        $fileHandler = new FileHandler(
            parser: new ParserTest(),
            outputFileStorage: $this->outputStorage,
            inputFileStorage: $this->inputStorage,
            inputFileName: $this->getInputFileName(),
            outputFileName: $this->getOutputFileName()
        );

        $fileHandler->handle();
    }

    /**
     * @Then Вижу, что все поля обработаны верно
     */
    public function iSeeRightFile()
    {
        $realOutput = $this->outputStorage->getFileContent($this->getOutputFileName());
        $this->outputStorage->removeFile($this->getOutputFileName());

        $rightOutput = $this->inputStorage->getFileContent($this->getRightOutputFileName());
        $this->assertEquals($realOutput, $rightOutput);
    }

    private function getInputFileName(): string
    {
        return 'input.txt';
    }

    private function getRightOutputFileName(): string
    {
        return 'right-output.jsonl';
    }

    private function getOutputFileName(): string
    {
        return 'real-output.jsonl';
    }
}
