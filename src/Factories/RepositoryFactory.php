<?php
declare(strict_types = 1);

namespace App\Factories;

use App\Repositories\PatternsRepository;
use App\Repositories\RelationsRepository;
use App\Repositories\WordsRepository;

class RepositoryFactory
{
    public function createRepository(string $param): object
    {
        $class = $param . 'Repository';
        switch (($class)) {
            case 'WordsRepository':
                $repository = new WordsRepository();
                break;
                case 'PatternsRepository':
                   $repository =  new PatternsRepository();
                    break;
                    case 'RelationsRepository':
                        $repository = new RelationsRepository();
                        break;
        }
        return $repository;
    }
}
