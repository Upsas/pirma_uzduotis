<?php

declare(strict_types=1);

namespace App;

use App\Controllers\IndexController;
use App\Controllers\PatternController;
use App\Controllers\WordsController;

class Router
{
    public function __construct()
    {
        $this->controller = new WordsController();
        $this->indexController = new IndexController();
        $this->patternController = new PatternController();
    }
        
    /**
     * @return void
     */

    public function routes(): void
    {
        // pakeisti veliau url
        switch ($_SERVER['REQUEST_URI'] === '/praktika/src/main.php') {
            case ($_SERVER['REQUEST_METHOD'] === 'GET'):
                $this->controller->getAllHyphenatedWords();
                break;
            case ($_SERVER['REQUEST_METHOD'] === 'POST'):
                $this->controller->insertDataToDb($_POST['word']);
                break;
            case ($_SERVER['REQUEST_METHOD'] === 'PUT'):
                parse_str(file_get_contents("php://input"), $data);
                $oldWord = $data['word'];
                $newWord = $data['newWord'];
                $this->controller->editData($oldWord, $newWord);
                break;
            case ($_SERVER['REQUEST_METHOD'] === 'DELETE'):
                parse_str(file_get_contents("php://input"), $data);
                $this->controller->deleteWordFromDb($data['word']);
                break;
            default:
                header('HTTP/1.1 405 Method Not Allowed');
                header('Allow: GET, PUT, POST, DELETE');
        }
    }

    public function indexRoutes(): void
    {
        switch ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
            case (isset($_POST['delete'])):
                $this->indexController->deleteWord($_POST['id']);
                break;
            case (isset($_POST['add'])):
                    $this->indexController->addWord($_POST['word']);
                break;
            case (isset($_POST['edit'])):
                $this->indexController->updateWord(
                    $_POST['oldWord'],
                    $_POST['word']
                );
                break;
                case (isset($_POST['searchButton'])):
                $this->indexController->searchWord($_POST['searchValue']);
                break;
            default:
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET, PUT, POST, DELETE');
        }
    }

    public function patternRoutes(): void
    {
        switch ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
            case (isset($_POST['add']) && !empty($_POST['pattern'])):
                    $this->patternController->addPatternToDb($_POST['pattern']);
                break;
                case (isset($_POST['delete'])):
                $this->patternController->deletePatternFromDb($_POST['id']);
                break;
                case (isset($_POST['searchButton'])):
                $this->patternController->searchPattern(intval($_POST['searchValue']));
                break;
            default:
            header('HTTP/1.1 405 Method Not Allowed');
            header('Allow: GET, PUT, POST, DELETE');
        }
    }
}
