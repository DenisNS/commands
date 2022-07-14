<?php
namespace DenisNS\Commands;

use DenisNS\Commands\Exceptions\BadInputException;

/**
 * Базовый класс команды
 */
abstract class AbstractCommand
{
    // описание команды
    protected string $description;
    // пользовательский ввод
    protected string $input;
    // название команды
    protected string $command;
    /**
     * аргументы и опции обрабатываемые командой
     * @var array $arguments = []
     * @var array $options = [name => [value1, ...],...]
     */
    protected array $arguments, $options;

    /**
     * Метод конструктор
     * $input = null только при использовании команды для показа подсказок
     * @param string|null $input
     */
    public function __construct(string $input = null)
    {
        if (!is_null($input)) {
            $this->input = str_replace(" ", '', $input);
            try {
                $this->check();
            } catch (BadInputException $e) {
                echo($e->getErrorMessage());
                die();
            }
        }
    }

    /**
     * Метод обработчик команды
     */
    public abstract function run();

    /**
     * Проверка опций
     * Возвращает массив опции со значениями либо false,
     * если $option = null возвращает список всех опций
     * @param string|null $option
     * @return array|false|mixed
     */
    protected function getOption(string $option = null)
    {
        $options = [];
        $input = $this->input;
        $input = substr($input, stripos($input, '[') + 1);
        $input = substr($input, 0, strrpos($input, ']'));
        $input = str_replace(' ', '',$input);
        $input = str_replace(']', ';]',$input);


        while (stripos($input, ']') > 0) {
            $input = substr($input,0, stripos($input, ']'))
                . substr($input, stripos($input, '[') + 1);
        }
        if (strlen($input) > 0) {
            $input = explode(';', $input);
            foreach ($input as $item) {
                $explode_item = explode('=', $item);
                $option_name = $explode_item[0];
                $option_value = $explode_item[1];
                if (!array_key_exists($option_name, $options)) {
                    $options[$option_name] = [];
                }
                if (stripos($item,'{') > 0) {
                    $option_value = explode(',', str_replace('{', '',
                        str_replace('}', '', $option_value)));
                }
                if (!is_array($option_value)) {
                    $options[$option_name][] = $option_value;
                } else {
                    $options[$option_name] = array_unique(array_merge($option_value,$options[$option_name]));
                }
            }

            if (!is_null($option)) {
                if (array_key_exists($option, $options)) {
                    return $options[$option];
                }
                else {
                    return false;
                }
            } else {
                return $options;
            }
        }

        return false;
    }

    /**
     * Проверка аргументов
     * Возвращает true либо false,
     * если $argument = null возвращает список всех аргументов
     * @param string|null $argument
     * @return array|false|mixed
     */
    protected function getArgument(string $argument = null)
    {
        $input = $this->input;

        while (stripos($input, '[') > 0) {
            $input = substr($input,0, stripos($input, '[')) .
                substr($input, stripos($input, ']') + 1);
        }
        $input = str_replace(' ', '', substr($input, stripos($input, '{')));
        $input = str_replace('{', ',', $input);
        $input = str_replace('}', ',', $input);
        $input = str_replace(',,', ',', $input);
        $input = trim($input, ',');

        if (strlen($input) > 0) {
            $arguments = explode(',', $input);

            if (is_array($arguments)) {
                if (!is_null($argument)) {
                    return in_array($argument, $arguments);
                } else {
                    return $arguments;
                }
            }
        }

        return false;

    }

    /**
     * Проверяет правильность ввода
     * и присутствие ключевого слова help
     * @return void
     * @throws BadInputException
     */
    private function check()
    {
        $arguments = $this->getArgument();
        $options = $this->getOption();

        $diff_arguments = [];

        if (is_array($arguments)) {
            $diff_arguments = array_diff($arguments, $this->arguments);
            if (($key = array_search('help', $diff_arguments)) !== false) {
                unset($diff_arguments[$key]);
                return $this->help();
            }
        }

        $diff_options = [];
        $error_value_options = [];

        if (is_array($options)) {
            $diff_options = array_keys(array_diff_key($options, $this->options));

            foreach ($options as $name => $option) {
                if (!in_array($name, $diff_options)) {
                    if (is_array($option)) {
                        $errors = array_diff($option, $this->options[$name]);
                        if (count($errors) > 0) {
                            $error_value_options[] = $name;
                        }
                    } else {
                        if ($this->options[$name] !== $option){
                            $error_value_options[] = $name;
                        }
                    }
                }
            }
        }

        if (count($diff_arguments) > 0
            || count($error_value_options) > 0
            || count($diff_options) > 0) {
            throw new BadInputException($diff_arguments, $diff_options, $error_value_options);
        }
    }

    /**
     * Выводит информацию о команде
     * @return void
     */
    public function help()
    {
        echo 'Command: '. $this->command. "\r\n";
        echo 'Description: '. $this->description. "\r\n";
        echo "Arguments: \r\n";
        foreach ($this->arguments as $argument) {
            echo "\t - ". $argument."\r\n";
        }
        echo "Options: \r\n";

        foreach ($this->options as $option_name => $option_values) {
            echo "\t - ". $option_name."\r\n ";
            foreach ($option_values as $option_value) {
                echo "\t\t - ". $option_value."\r\n";
            }
        }
    }
}
