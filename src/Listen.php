<?php

namespace DenisNS\Commands;

class Listen
{
    private array $args;
    private string $command;
    private string $signature = '';


    public function setArgs(array $args)
    {
        $this->args = $args;
        if (count($args) > 1)
        {
            $this->command = $this->args[1];
            $this->setSignature(array_slice($this->args, 2));
        }

    }

    private function setSignature(array $args)
    {
        foreach ($args as $arg)
        {
            if (substr($arg, 0, 1) !== '['
                && substr($arg, 0, 1) !== '{')
            {
                $this->signature .= '{'.$arg.'}';
            }
            else
            {
                $this->signature .= $arg;
            }
        }
    }

    public function getCommandName()
    {
        $commandName = '';
        foreach (explode('_', $this->command) as $word)
        {
            $commandName .= ucfirst(strtolower($word));
        }
        return $commandName;
    }

    public function getSignature()
    {
        return $this->signature;
    }

    public static function showHelpMessage(string $commands_dir, string $commands_namespace)
    {
        echo "This application have follow commands: \r\n";
        foreach (glob($commands_dir . '/*.php') as $file)
        {
            require_once $file;

            // get the file name of the current file without the extension
            // which is essentially the class name
            $class = $commands_namespace . basename($file, '.php');
            if (class_exists($class))
            {
                $obj = new $class;
                $obj->help();
            }
        }
    }
}