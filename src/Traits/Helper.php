<?php
namespace DenisNS\Commands\Traits;

trait Helper
{
    public static function showHelpMessage(string $commands_dir, string $commands_namespace)
    {
        echo "This application have follow commands: \r\n";
        foreach (glob($commands_dir . '/*.php') as $file) {
            require_once $file;

            // get the file name of the current file without the extension
            // which is essentially the class name
            $class = $commands_namespace . basename($file, '.php');
            if (class_exists($class)) {
                $obj = new $class();
                $obj->help();
            }
        }
    }
}