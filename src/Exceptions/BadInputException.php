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
        $this->message = "Error: \r\n";
        if (count($this->arguments) > 0) {
            $this->message .= '- argument(s): '
                . implode(',', $this->arguments)." are not processed by this command; \r\n";
        }
        if (count($this->options) > 0) {
            $this->message .= '- option(s): '
                . implode(',', $this->options)." are not processed by this command; \r\n";
        }
        if (count($this->options_value) > 0) {
            $this->message .= '- option(s):  '
                . implode(',', $this->options_value)." have values that are not processed; \r\n";
        }
        $this->message .= "For help input {help} \r\n";
        return $this->message;
    }
}