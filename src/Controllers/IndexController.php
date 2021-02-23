<?php

namespace App\Controllers;

use App\App;
use App\Repositories\WordsRepository;
use App\Controllers\WordsController;
use App\Router;

class IndexController
{
    public function __construct()
    {
        $this->wordsRepository = new WordsRepository();
        $this->wordsController = new WordsController();
    }

    public function getAllData()
    {
        return  $this->wordsRepository->getAllDataFromWordsDb();
    }

    public function deleteWord($word)
    {
        $this->wordsController->deleteWordFromDb($word);
        // $id = (intval(trim($_POST['id'])));
        // $this->wordsRepository->deleteWord($id);
        header('location: /praktika/src/Views/');
    }

    public function addWord($word)
    {
        // $routers = new Router();
        // $app = new App($routers);
        // $app->addWordToDb($_POST['word']);
        $this->wordsController->insertDataToDb($word);
        header('location: /praktika/src/Views/');
    }

    public function updateWord($oldWord, $newWord)
    {
        $this->wordsController->editData($oldWord, $newWord);
        header('location: /praktika/src/Views/');
    }

    public function searchWord($word)
    {
        $this->wordsRepository->getWordDataFromDb($word);
    }

    public function pagination()
    {
        $resultsPerPage = 2;
        $numberOfPages = ceil(count($this->getAllData()) / $resultsPerPage);

        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        $thisPageFirstResult = ($page - 1) * $resultsPerPage;


        for ($page = 1; $page <= $numberOfPages; $page++) {
            echo '<a href="index.php?page=' . $page . '">' . $page . '</a>';
        }
        return  $this->wordsRepository->getLimitedDataFromDb($thisPageFirstResult, $resultsPerPage);
    }
}
