<?php
declare (strict_types = 1);

namespace Kkdshka\TodoListCli;

/**
 * Writes text in a command line.
 * @author Ксю
 */
class Writer {
    
    /**
     * Write expression in a command line.
     * @param string $expression
     */
    public function write(string $expression) {
        print $expression;
    }
}
