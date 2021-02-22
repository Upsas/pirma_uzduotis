<?php

declare(strict_types=1);

namespace App\Tests;

use App\Pattern\Hyphenator;
use App\Pattern\PatternReader;
use PHPUnit\Framework\TestCase;

/**
* @coversDefaultClass App\Pattern\Hyphenator
*/

class HyphenatorTest extends TestCase
{
    protected function setUp(): void
    {
        $this->patternReader = new PatternReader();
        $this->file = './Assets/tex-hyphenation-patterns.txt';
        $this->patterns =  $this->patternReader->getPatterns($this->file);
        $this->hyphenator = new Hyphenator($this->patterns);
    }
    
    /**
    * @covers ::__construct
    * @covers ::getSelectedPatterns
    * @covers App\Pattern\Pattern::__construct
    * @covers App\Pattern\Pattern::getPattern
    * @covers App\Pattern\PatternReader::checkIfFileExists
    * @covers App\Pattern\PatternReader::getPatterns
    */

    public function testGetSelectedPatterns()
    {
        $pattern = ['.te4', '4as.', '1ta'];
        $this->assertEquals($this->hyphenator->getSelectedPatterns('testas'), $pattern);
    }

    /**
    * @covers ::__construct
    * @covers ::getPositionOfPattern
    * @covers App\Pattern\Pattern::__construct
    * @covers App\Pattern\PatternReader::checkIfFileExists
    * @covers App\Pattern\PatternReader::getPatterns
    */

    public function testGetPositionOfPattern()
    {
        $positionOfPattern = $this->hyphenator->getPositionOfPattern('testas', ' .te');
        $this->assertIsInt($positionOfPattern);
        $this->assertEquals(0, $positionOfPattern);
    }

    /**
    * @covers ::__construct
    * @covers ::populateNumbersArray
    * @covers App\Pattern\Pattern::__construct
    * @covers App\Pattern\PatternReader::checkIfFileExists
    * @covers App\Pattern\PatternReader::getPatterns
    */

    public function testPopulateNumbersArray()
    {
        $numbersArray = [];
        $position = 0;
        $this->assertIsArray($this->hyphenator->populateNumbersArray($numbersArray, $position, '4te'));
    }

    /**
    * @covers ::__construct
    * @covers ::getPositionOfPattern
    * @covers ::populateNumbersArray
    * @covers ::populatePositionWithNumber
    * @covers App\Pattern\Pattern::__construct
    * @covers App\Pattern\PatternReader::checkIfFileExists
    * @covers App\Pattern\PatternReader::getPatterns
    */

    public function testPopulatePositionWithNumber()
    {
        $pattern = ['.te4', '4as.', '1ta'];
        $this->assertIsArray($this->hyphenator->populatePositionWithNumber('testas', $pattern));
    }

    /**
    * @covers ::__construct
    * @covers ::mergeNumbersArray
    * @covers ::getPositionOfPattern
    * @covers ::populateNumbersArray
    * @covers ::populatePositionWithNumber
    * @covers App\Pattern\Pattern::__construct
    * @covers App\Pattern\PatternReader::checkIfFileExists
    * @covers App\Pattern\PatternReader::getPatterns
    */

    public function testMergeNumbersWithWord()
    {
        $pattern = ['.te4', '4as.', '1ta'];
        $a = $this->hyphenator->populatePositionWithNumber('testas', $pattern);
        $this->assertIsArray($this->hyphenator->mergeNumbersWithWord('testas', $a));
    }

    /**
    * @covers ::__construct
    * @covers ::hyphenate
    * @covers ::mergeNumbersArray
    * @covers ::getPositionOfPattern
    * @covers ::populateNumbersArray
    * @covers ::populatePositionWithNumber
    * @covers App\Pattern\Pattern::__construct
    * @covers App\Pattern\PatternReader::checkIfFileExists
    * @covers App\Pattern\PatternReader::getPatterns
    */
       
    public function testHyphenate()
    {
        $pattern = ['.te4', '4as.', '1ta'];
        $a = $this->hyphenator->populatePositionWithNumber('testas', $pattern);
        $this->assertIsString($this->hyphenator->hyphenate('testas4', $a));
    }
}
