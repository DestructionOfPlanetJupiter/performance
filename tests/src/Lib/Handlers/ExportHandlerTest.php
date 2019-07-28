<?php

declare(strict_types=1);

namespace tests\src\Lib\Handlers;

use Performance\Lib\Handlers\ExportHandler;
use Performance\Lib\Handlers\PerformanceHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class ExportHandlerTest
 * @package tests\src\Lib\Handlers
 */
class ExportHandlerTest extends TestCase
{
    /**
     * @var ExportHandler
     */
    protected $instance;

    /**
     * @var PerformanceHandler
     */
    protected $mock;

    public function testConfigWithNoPoints(): void
    {
        //Arrange
        $expected = clone $this->instance;

        //Act
        $export = $this->instance->config();

        //Assert
        $this->assertEquals($expected, $export);
    }

    public function testConfigWithPoints(): void
    {
        //Arrange
        $performanceHandler = new PerformanceHandler();
        $performanceHandler->point('123', false);
        $instance = new ExportHandler($performanceHandler);

        //Act
        $export = $this->instance->config();

        //Assert
        $this->assertEquals($export, $instance->config());
    }

    protected function setUp(): void
    {
        /** @var PerformanceHandler $mock */
        $this->mock = $this->createMock(PerformanceHandler::class);
        $this->instance = new ExportHandler($this->mock);
    }
}