<?php

namespace DenisNS\Commands;

class Listen
{
    private array $args;
    private string $command;
    private string $signature = '';


    public function __construct(array $args)
    {
        $this->args = $args;
        if (count($args) > 1) {
            $this->command = $this->args[1];
            $this->setSignature(array_slice($this->args, 2));
        }

    }

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

    public function getCommandName()
    {
        $commandName = '';
        foreach (explode('_', $this->command) as $word){
            $commandName .= ucfirst(strtolower($word));
        }
        return $commandName;
    }

    public function getSignature()
    {
        return $this->signature;
    }
}