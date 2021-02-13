<?php

use Controller;

class Router
{
    public function __construct()
    {
        $this->controller = new Controller();
    }
    public function routes()
    {
        switch ($_SERVER['REQUEST_URI'] === '/praktika/src/') {
            case ($_SERVER['REQUEST_METHOD'] === 'GET'):
                $this->controller->getAllHyphenatedWords();
                break;
            case ($_SERVER['REQUEST_METHOD'] === 'POST'):
                $this->controller->insertDataToDb();
                break;
            case ($_SERVER['REQUEST_METHOD'] === 'PUT'):
                $this->controller->editData();
                break;
            case ($_SERVER['REQUEST_METHOD'] === 'DELETE'):
                $this->controller->deleteWordFromDb();
                break;
        }

    }
}
