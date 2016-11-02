#! php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Kkdshka\TodoList\Model\TaskManager;
use Kkdshka\TodoList\Repository\RepositoryFactory;
use Kkdshka\TodoListCli\Cli;
use Kkdshka\TodoListCli\Reader;
use Kkdshka\TodoListCli\Writer;
//"csv:C:/Development/Temp/todolist.csv"

function printHelp() {
    echo "Usage: todolist.php [options]\n
        [options]:\n
        -h, --help          Prints this usage information\n
        -s <connection Url> Starts the program and create repository\n
        <connection Url> might be in format csv:path/to/repository or sqlite:path/to/repository\n";
}

if (array_search("-h", $argv) || array_search("--help", $argv)) {
    printHelp();
    return;
}

if (!array_search("-s", $argv)) {
    printHelp();
    return;
}

$connectionUrl = $argv[2];
$repository = (new RepositoryFactory)->create($connectionUrl);
(new Cli(new Reader, new Writer, new TaskManager($repository)))->run();




