<?php

namespace IncidentCenter\RL\CloudFunctions\LibParserOpenRfmFullList\Exception;
use Exception;
class ParserException extends Exception
{
    private string $ExceptionMessage;
    public function __construct(string $message)
    {
        $this->ExceptionMessage = $message;
    }

    /**
     * @return string
     */
    public function getExceptionMessage(): string
    {
        return $this->ExceptionMessage;
    }
}