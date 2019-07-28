<?php

namespace tests\src\Lib\Holders;

use Performance\Lib\Handlers\ConfigHandler;
use Performance\Lib\Handlers\ExportHandler;
use Performance\Lib\Handlers\PerformanceHandler;
use PHPUnit\Framework\TestCase;

/**
 * Class ExportHandlerTest
 * @package tests\src\Lib\Holders
 */
class ExportHandlerTest extends TestCase
{
    /**
     * @var ExportHandler
     */
    private $instance;

    /**
     * @covers \Performance\Lib\Handlers\ExportHandler::get
     */
    public function testExportHandlerGet()
    {
        //Arrange
        $expected = [
            'points' => [],
            'config' => new ConfigHandler()
        ];

        //Act
        $actual = $this->instance->get();

        //Assert
        $this->assertEquals($expected, $actual);
    }


    public function testExportHandlerToJson()
    {
        //Arrange

        //Act
        $actual = $this->instance->toJson();

        //Assert
        $this->assertInternalType('string', $actual);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->instance = new ExportHandler(new PerformanceHandler());
    }
}