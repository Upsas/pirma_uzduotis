<?php
declare(strict_types = 1);

namespace App;

use Controllers\WordsController;

class Router
{
    public function __construct()
    {
        $this->controller = new WordsController();
    }
        
    /**
     * @return void
     */

    public function routes():void
    {
        switch ($_SERVER['REQUEST_URI'][-1] === '/') {
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
