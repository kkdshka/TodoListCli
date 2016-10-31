<?php

namespace Kkdshka\TodoListCli;

use PHPUnit\Framework\TestCase;
use Phake;
use Kkdshka\TodoList\Model\TaskManager;
use Kkdshka\TodoList\Model\Task;

/**
 * Description of CliTest
 *
 * @author Ксю
 */
class CliTest extends TestCase {
    
    private $reader;
    private $writer;
    private $taskManager;
    const COMANDS = "Please choose command:\nn - create\nc - complete\nd - delete\na - printAll\nq - quit\n";
    
    
    public function setUp() {
        $this->reader = Phake::mock(Reader::class);
        $this->writer = Phake::mock(Writer::class);
        $this->taskManager = Phake::mock(TaskManager::class);
    }
    
   /**
    * @test
    */
    public function shouldQuit() {
        Phake::when($this->reader)->read()->thenReturn('q');
        
        (new Cli($this->reader, $this->writer, $this->taskManager))->run();
        
        Phake::verify($this->writer)->write(self::COMANDS);
        Phake::verify($this->reader)->read();
        Phake::verify($this->writer)->write('Bye!');
        Phake::verifyNoOtherInteractions($this->reader);
        Phake::verifyNoOtherInteractions($this->writer);
    }
    
    /**
     * @test
     */
    public function shouldCreate() {
        Phake::when($this->reader)->read()
            ->thenReturn('n')
            ->thenReturn('Test subject')
            ->thenReturn('q');
        
        (new Cli($this->reader, $this->writer, $this->taskManager))->run();
        
        Phake::verify($this->writer, Phake::times(2))->write(self::COMANDS);
        Phake::verify($this->reader, Phake::times(3))->read();
        Phake::verify($this->writer)->write("Enter task's subject:\n");
        Phake::verify($this->taskManager)->create('Test subject');
        Phake::verify($this->writer)->write('Bye!');
        Phake::verifyNoOtherInteractions($this->reader);
        Phake::verifyNoOtherInteractions($this->writer);
        Phake::verifyNoOtherInteractions($this->taskManager);
    }
    
    /**
     * @test
     */
    public function shouldComplete() {
        $task = Phake::mock(Task::class);
        Phake::when($this->reader)->read()
            ->thenReturn('c')
            ->thenReturn(111)
            ->thenReturn('q');
        Phake::when($this->taskManager)->findTaskById(111)->thenReturn($task);
        
        (new Cli($this->reader, $this->writer, $this->taskManager))->run();
        
        Phake::verify($this->writer, Phake::times(2))->write(self::COMANDS);
        Phake::verify($this->reader, Phake::times(3))->read();
        Phake::verify($this->writer)->write("Enter task's id to complete:\n");
        Phake::verify($this->taskManager)->findTaskById(111);
        Phake::verify($this->taskManager)->complete($task);
        Phake::verify($this->writer)->write('Bye!');
        Phake::verifyNoOtherInteractions($this->reader);
        Phake::verifyNoOtherInteractions($this->writer);
        Phake::verifyNoOtherInteractions($this->taskManager);
    }
    
    /**
     * @test
     */
    public function shouldDelete() {
        $task = Phake::mock(Task::class);
        Phake::when($this->reader)->read()
            ->thenReturn('d')
            ->thenReturn(111)
            ->thenReturn('q');
        Phake::when($this->taskManager)->findTaskById(111)->thenReturn($task);
        
        (new Cli($this->reader, $this->writer, $this->taskManager))->run();
        
        Phake::verify($this->writer, Phake::times(2))->write(self::COMANDS);
        Phake::verify($this->reader, Phake::times(3))->read();
        Phake::verify($this->writer)->write("Enter task's id to delete:\n");
        Phake::verify($this->taskManager)->findTaskById(111);
        Phake::verify($this->taskManager)->delete($task);
        Phake::verify($this->writer)->write('Bye!');
        Phake::verifyNoOtherInteractions($this->reader);
        Phake::verifyNoOtherInteractions($this->writer);
        Phake::verifyNoOtherInteractions($this->taskManager);
    }
    
    /**
     * @test
     */
    public function shouldPrintAll() {
        $task = Phake::mock(Task::class);
        Phake::when($this->reader)->read()
            ->thenReturn('a')
            ->thenReturn('q');
        Phake::when($this->taskManager)->getAll()->thenReturn([$task]);
        Phake::when($task)->isCompleted()->thenReturn(true);
        Phake::when($task)->getId()->thenReturn(111);
        Phake::when($task)->getSubject()->thenReturn("Test subject");
        
        (new Cli($this->reader, $this->writer, $this->taskManager))->run();
        
        Phake::verify($this->writer, Phake::times(2))->write(self::COMANDS);
        Phake::verify($this->reader, Phake::times(2))->read();
        Phake::verify($this->taskManager)->getAll();
        Phake::verify($task)->isCompleted();
        Phake::verify($task)->getId();
        Phake::verify($task)->getSubject();
        Phake::verify($this->writer)->write("111 | Test subject | completed\n");
        Phake::verify($this->writer)->write('Bye!');
        Phake::verifyNoOtherInteractions($this->reader);
        Phake::verifyNoOtherInteractions($this->writer);
        Phake::verifyNoOtherInteractions($this->taskManager);
        Phake::verifyNoOtherInteractions($task);
    }
}
