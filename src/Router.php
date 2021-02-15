<?php

use Controllers\WordsController;

class Router
{
    public function __construct()
    {
        $this->controller = new WordsController();
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
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                header('Allow: GET, PUT, POST, DELETE');
        }

    }
}
