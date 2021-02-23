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
        $this->file = './src/Assets/tex-hyphenation-patterns.txt';
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
    * @covers ::hyphenate
    * @covers ::mergeNumbersWithWord
    * @covers ::getSelectedPatterns
    * @covers ::getPositionOfPattern
    * @covers ::populateNumbersArray
    * @covers ::populatePositionWithNumber
    * @covers App\Pattern\Pattern::__construct
    * @covers App\Pattern\Pattern::getPattern
    * @covers App\Pattern\PatternReader::checkIfFileExists
    * @covers App\Pattern\PatternReader::getPatterns
    * @covers App\Pattern\Pattern::stripNumbers
    * @covers App\Pattern\PatternReader::__construct
    */
       
    public function testHyphenate()
    {
        $pattern = ['.te4', '4as.', '1ta'];
        $this->assertIsString($this->hyphenator->hyphenate('testas4', $pattern));
    }
}
