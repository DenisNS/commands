<?php

namespace DenisNS\Commands;

/**
 * Класс для определения класса команды и
 * передачи преобраотанного пользовательского ввода команде
 */
class Listen
{
    // пользовательский ввод
    private array $args;
    // название класса команды
    private string $command;
    // преобработанный пользовательский ввод
    private string $signature = '';

    /**
     * @param array $args
     */
    public function __construct(array $args)
    {
        $this->args = $args;
        if (count($args) > 1) {
            $this->command = $this->args[1];
            $this->setSignature(array_slice($this->args, 2));
        }

    }

    /**
     * Устанавливает
     * преобработанный пользовательский ввод
     * в переменнуую signature
     * @param array $args
     * @return void
     */
    private function setSignature(array $args)
    {
        foreach ($args as $arg) {
            if (substr($arg, 0, 1) !== '['
                && substr($arg, 0, 1) !== '{') {
                $this->signature .= '{'.$arg.'}';
            } else {
                $this->signature .= $arg;
            }
        }
    }

    /**
     * Возвращает
     * имя класса команды
     * @return string
     */
    public function getCommandName()
    {
        $commandName = '';
        foreach (explode('_', $this->command) as $word){
            $commandName .= ucfirst(strtolower($word));
        }
        return $commandName;
    }

    /**
     * Возвращает
     * преобработанный пользовательский ввод
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }
}