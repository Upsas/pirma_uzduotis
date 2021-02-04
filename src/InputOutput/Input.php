<?php
namespace InputOutput;
class Input
{
    public $string;

    public function getUserInput()
    {
        $this->string = readline('Enter a word: ');
        echo $this->string . PHP_EOL;
        return $this->string;
    }

}
