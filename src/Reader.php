<?php
declare (strict_types = 1);

namespace Kkdshka\TodoListCli;

/**
 * Reads text entered in a command line.
 * @author Ксю
 */
class Reader {
    
    /**
     * Returns entered text.
     * @return string
     */
    public function read() : string {
        return fgets(STDIN);
    } 
}
