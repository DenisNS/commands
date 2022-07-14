# Создание собственных команд

1. Создайте директорию для ваших команд.
2. Создайте нужные вам команды, для этого нужно наследовать класс AbstractCommand. 
3. Задать свойства таким образом:


        protected string $description = "Описание команды";
        protected string $command = 'название команды'; // в нижнем регистре разделитель "_" ,
        //то есть класс _SomeCommand_, будет иметь название _some_command_
        protected array $arguments = ['значение1','значение2','значение3',...];
        protected array $options = ['название' => ['значение1', 'значение2',...],...]


Создайте метод _run()_, метод обработчик

Для обработки поступающих данных используйте методы _getArgument()_ и _getOption()_

При вызовае без параметров возвращают массив, при вызове с необходимым именем _getArgument()_ 
вернет bool(присутствует или нет аргумент в строке ввода) _getOption()_ либо bool false либо массив со значениями  

# Интеграция в консольно приложение
Для интеграции в консольное приложение добавьте:

    use DenisNS\Commands\Listen;
    use DenisNS\Commands\Traits\Helper;

    require __DIR__ . '/vendor/autoload.php'; 

    if (count($argv) > 1) {
        $listen = new Listen($argv);
        $commandName = OPERATIONS_NAMESPACE .$listen->getCommandName();
        $signature = $listen->getSignature();
    
        $command = new $commandName($signature);
        $command->run();
    } else {
        (new class { use Helper; })::showHelpMessage(OPERATIONS_DIR, OPERATIONS_NAMESPACE);
    
    }
*OPERATIONS_NAMESPACE - замените на строку namespace директории где лежат ваши команды

*OPERATIONS_DIR - замените на строку до директории где лежат ваши команды
