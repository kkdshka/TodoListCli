<?php

declare (strict_types = 1);

namespace Kkdshka\TodoListCli;

use Kkdshka\TodoList\Model\TaskManager;

/**
 * Command line interface for Kkdshka\TodoList.
 * @author Ксю
 */
class Cli {

    /**
     * Manages tasks.
     * @var TaskManager
     */
    private $taskManager;
    
    /**
     * Reads text from a command line.
     * @var Reader
     */
    private $reader;
    
    /**
     * Writes text in a command line.
     * @var Writer
     */
    private $writer;

    /**
     * @param Reader $reader Reads text from a command line.
     * @param Writer $writer Writes text in a command line.
     * @param TaskManager $taskManager Manages tasks.
     */
    public function __construct(Reader $reader, Writer $writer, TaskManager $taskManager) {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->taskManager = $taskManager;
    }

    /**
     * Main loop which reads inserted data and decides which command to execute.
     */
    public function run() {
        $insertedData = "";
        do {
            $this->writer->write("Please choose command:\nn - create\nc - complete\nd - delete\na - printAll\nq - quit\n");
            $insertedData = trim($this->reader->read());
            switch ($insertedData) {
                case "n":
                    $this->create();
                    break;
                case "c":
                    $this->complete();
                    break;
                case "d":
                    $this->delete();
                    break;
                case "a":
                    $this->printAll();
                    break;
                case "q":
                    $this->writer->write("Bye!\n");
                    break;
                default:
                    $this->writer->write("Unknown command, try again.");
            }
        } while ($insertedData != "q");
    }

    /**
     * Creates a new task.
     */
    private function create() {
        $this->writer->write("Enter task's subject:\n");
        $subject = $this->reader->read();
        $this->taskManager->create($subject);
    }

    /**
     * Completes the task.
     */
    private function complete() {
        $this->writer->write("Enter task's id to complete:\n");
        $id = $this->reader->read();
        $task = $this->taskManager->findTaskById((int) $id);
        $this->taskManager->complete($task);
    }

    /**
     * Deletes the task.
     */
    private function delete() {
        $this->writer->write("Enter task's id to delete:\n");
        $id = $this->reader->read();
        $task = $this->taskManager->findTaskById((int) $id);
        $this->taskManager->delete($task);
    }

    /**
     * Print all tasks.
     */
    private function printAll() {
        $data = $this->taskManager->getAll();
        foreach ($data as $task) {
            if ($task->isCompleted()) {
                $isCompleted = "completed";
            } else {
                $isCompleted = "in progress";
            }
            $this->writer->write("{$task->getId()} | {$task->getSubject()} | $isCompleted\n");
        }
    }
}
