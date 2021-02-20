<?php

declare(strict_types=1);

namespace App\Tests;

use App\InputOutput\Output;
use App\Log\Log;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass App\InputOutput\Output
 */
class OutputTest extends TestCase
{
        
    /**
     * @covers ::outputResult()
     * @covers ::__construct
     */

    public function testOutputResult(): void
    {
        // $mock = $this->createMock(Output::class);
        // $mock->expects($this->any())
        //          ->method('outputResult')
        //          ->with(('string'));
        // $this->expectOutputString($mock->outputResult('string') . "string\n");

        // praeina su 100% coverage
        $output = new Output(new log());
        $this->expectOutputString($output->outputResult('string') . "string\n");
    }
}
