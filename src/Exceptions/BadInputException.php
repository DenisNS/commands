<?php

namespace DenisNS\Commands\Exceptions;

use mysql_xdevapi\Exception;

class BadInputException extends Exception
{
    private array $arguments, $options, $options_value;

    public function __construct(array $arguments = [], array $options = [], array $options_value = []) {
        $this->options = $options;
        $this->options_value = $options_value;
        $this->arguments = $arguments;
        $this->message = $this->setMessage();
    }

    public function setMessage()
    {
        $this->message = 'Error: ';
        if (count($this->arguments) > 0)
        {
            $this->message .= 'these '.implode(',', $this->arguments).' are not processed by this command; ';
        }
        if (count($this->options) > 0)
        {
            $this->message .= 'these '.implode(',', $this->options).' are not processed by this command';
        }
        if (count($this->options_value) > 0)
        {
            $this->message .= 'these '.implode(',', $this->options_value).'have values that are not processed';
        }
        return $this->message;
    }


}