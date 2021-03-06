<?php

namespace App\Repositories;

use App\Repositories\QueryBuilder;
use App\Database\DatabaseConnection;

class WordsRepository extends DatabaseConnection
{
    private $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder();
    }
    /**
     * @param  string $word
     * @return string|null
     */

    public function checkForDuplicates(?string $word): ?string
    {
        $word = $this->queryBuilder
        ->select('word')
        ->from('words')
        ->where(['word', $word])
        ->get();
        return ($word[0]->word ?? null);
    }
    
    /**
     * @param  string $word
     * @return string|null
     */
    
    public function getHyphenatedWordFromDb(string $word): ?string
    {
        $hyphenatedWord = $this->queryBuilder
        ->select('hyphenated_word')
        ->from('words')
        ->where(['word', $word])
        ->get();
        return ($hyphenatedWord[0]->hyphenated_word ?? null);
    }
    
    /**
     * @return object[]
     */

    public function getAllHyphenatedWordsFromDb(): array
    {
        $hyphenatedWords = $this->queryBuilder
        ->select('hyphenated_word')
        ->from('words')
        ->get();

        return $hyphenatedWords;
    }
    
    /**
     * @return void
     */

    public function deleteWordsFromDb(): void
    {
        $this->queryBuilder
        ->from('words')
        ->deleteAll();
    }
    
    /**
     * @param  int $id
     * @return void
     */

    public function deleteWord(int $id): void
    {
        $this->queryBuilder
        ->from('words')
        ->where(['id', $id])
        ->delete();
    }
        
    /**
     * @param  string $word
     * @param  string $hyphenatedWord
     * @return void
     */

    public function addWords(string $word, string $hyphenatedWord): void
    {
        $this->queryBuilder
        ->from('words')
        ->where(['word', 'hyphenated_word'])
        ->values('?, ?')
        ->insert([$word, $hyphenatedWord]);
    }
    
    /**
     * @param  string $word
     * @return int|null
     */

    public function getWordId(string $word): ?int
    {
        $word = $this->queryBuilder
        ->select('id')
        ->from('words')
        ->where(['word', $word])
        ->get();
        return $word[0]->id ?? null;
    }
    
    /**
     * @return object[]
     */
    
    public function getAllWordsFromDb(): array
    {
        $words = $this->queryBuilder
        ->select('word')
        ->from('words')
        ->get();
        return $words;
    }
    
    /**
     *
     * @param  string $newWord
     * @param  string $newHyphenatedWord
     * @param  int $id
     * @return void
     */

    public function updateWord(string $newWord, string $newHyphenatedWord, int $id): void
    {
        $this->queryBuilder
        ->from('words')
        ->set('`word` = ?, `hyphenated_word` = ?')
        ->where(['id', $id])
        ->update([$newWord, $newHyphenatedWord]);
    }
    
    /**
     * get all data from words table
     *
     * @return array
     */
    public function getAllDataFromWordsDb(): array
    {
        $words = $this->queryBuilder
        ->from('words')
        ->all();
        return $words;
    }
    
    /**
     * getWordDataFromDb
     *
     * @param  string $word
     * @return array
     */
    public function getWordDataFromDb(string $word): array
    {
        $words = $this->queryBuilder
        ->from('words')
        ->where(['word', $word])
        ->all();
        return $words;
    }
    /**
     * getLimitedDataFromDb
     *
     * @param  int $start
     * @param  int $end
     * @return array
     */
    public function getLimitedDataFromDb(int $start, int $end): array
    {
        $words = $this->queryBuilder
        ->from('words')
        ->limitStart($start)
        ->limitEnd($end)
        ->getLimitedData();
        
        return $words;
    }

    public function getWordsById($id)
    {
        $word = $this->queryBuilder
        ->from('words')
        ->where(['id', $id])
        ->all();
        return $word;
    }
}
