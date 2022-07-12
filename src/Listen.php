<?php

namespace DenisNS\Command;

class Listen
{
    protected array $args;
    protected string $command;
    protected string $signature;


    public function __construct(array $args)
    {
        $this->args = $args;
        $this->command = $this->args[1];
        $this->signature = $this->setSignature(array_slice($this->args, 2));

    }

    private function setSignature(array $signature)
    {
        var_dump($signature);
    }
}