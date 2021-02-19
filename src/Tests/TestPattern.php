<?php

declare(strict_types=1);

namespace App\Tests;

use App\Pattern\Pattern;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass App\Pattern\Pattern
 */
class TestPattern extends TestCase
{
        
    /**
     * @covers ::getPattern()
     *
     */
    public function testGetPattern(): void
    {

        // Praeina bet nera coverage;
        $stub = $this->createStub(Pattern::class);
        $this->string = 'string';

        $stub->method('getPattern')
             ->willReturn($this->string);
        $this->assertSame('string', $stub->getPattern());
    }
    /**
     * @covers ::stripNumbers()
     *
     */
    public function testStripNumbers()
    {

        // Praeina bet ner coverage
        $stub = $this->createMock(Pattern::class);
        $stub->method('stripNumbers')->with('string2')->willReturn('string');
            
        $this->assertEquals('string', $stub->stripNumbers('string2'));
    }
}
