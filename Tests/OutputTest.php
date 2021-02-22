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
        $mock = $this->createMock(Log::class);
        $output = new Output($mock);
        $this->expectOutputString($output->outputResult('string') . "string\n");
    }
}
