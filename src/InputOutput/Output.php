<?php

namespace InputOutput;

class Output
{
    /**
     * Getting data from hypernate class and outputing for user
     *
     * @param  string $word
     * @return void
     */

    public function outputResult($word)
    {
        echo $word . PHP_EOL;

    }
}
