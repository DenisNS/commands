<?php

namespace DenisNS\Commands\Exceptions;

class BadInputException extends \Exception
{
    private array $arguments, $options, $options_value;

    public function __construct(array $arguments = [], array $options = [], array $options_value = []) {
        $this->options = $options;
        $this->options_value = $options_value;
        $this->arguments = $arguments;
    }

    public function getErrorMessage()
    {
        $this->message = 'Error: ';
        if (count($this->arguments) > 0)
        {
            $this->message .= 'these '.implode(',', $this->arguments)." are not processed by this command; \r\n";
        }
        if (count($this->options) > 0)
        {
            $this->message .= 'these '.implode(',', $this->options)." are not processed by this command; \r\n";
        }
        if (count($this->options_value) > 0)
        {
            $this->message .= 'these '.implode(',', $this->options_value)." have values that are not processed; \r\n";
        }
        return $this->message;
    }


}