<?php

class GetAlgoFile
{

    private $file = "./tex-hyphenation-patterns.txt";
    // protected function??? extendins tik tas klases kurioms reikia siu duomenu(failo)
    public function getDataFromFile()
    {
        if (file_exists($this->file)) {
            return file($this->file);

        } else {
            return false;
        }
    }
}
