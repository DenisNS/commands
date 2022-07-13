<?php

namespace DenisNS\Commands\Exceptions;

/**
 * Исключение неверного ввода
 */
class BadInputException extends \Exception
{
    private array $arguments, $options, $options_value;

    /**
     * @param array $arguments
     * @param array $options
     * @param array $options_value
     */
    public function __construct(array $arguments = [], array $options = [], array $options_value = []) {
        $this->options = $options;
        $this->options_value = $options_value;
        $this->arguments = $arguments;
    }

    /**
     * Возвращает форматированню строку ошибки
     * @return string
     */
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