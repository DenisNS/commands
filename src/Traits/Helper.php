<?php
namespace DenisNS\Commands\Traits;

/**
 * Отвечает за показ всех сууществующих команд,
 * при вызове приложения без команды
 */
trait Helper
{
    /**
     * @param string $commands_dir путь до директории команд
     * @param string $commands_namespace пространство имен в которм расположены команды
     * @return void выводит подсказки работы каждой из команд
     */
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