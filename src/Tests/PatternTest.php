<?php

declare(strict_types=1);

namespace App\Tests;

use App\Pattern\Pattern;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @coversDefaultClass App\Pattern\Pattern
 */
class PatternTest extends TestCase
{
        
    /**
     * @covers ::getPattern()
     * @covers ::__construct()
     */
    public function testGetPattern(): void
    {
        
        //nera coverage;
        // $stub = $this->createStub(Pattern::class);
        // $this->string = 'string';

        // $stub->method('getPattern')
        //      ->willReturn($this->string);
        // Su coverage
        $pattern = new Pattern('string');
        $this->assertEquals('string', $pattern->getPattern());
    }
    /**
     * @covers ::stripNumbers()
     * @covers ::__construct()
     */
    public function testStripNumbers()
    {
        // nera coverage
        // $mock = $this->createMock(Pattern::class);
        // $mock->expects($this->once())
        //          ->method('stripNumbers')
        //          ->with($this->equalTo('string2'))
        //          ->willReturn('string');
        
        // $this->assertSame($mock->stripNumbers('string2'), 'string');
        //
        // Su coverage
        $pattern = new Pattern('string');
        $this->assertSame($pattern->stripNumbers('string2'), 'string');
    }
}
