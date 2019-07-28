<?php

namespace tests\src\Lib\Holders;

use Performance\Lib\Configuration\PresenterConfiguration;
use Performance\Lib\Handlers\ConfigHandler;
use PHPUnit\Framework\TestCase;

class ConfigHandlerTest extends TestCase
{
    /**
     * @var ConfigHandler
     */
    private $instance;

    /**
     * @covers \Performance\Lib\Handlers\ConfigHandler::export
     */
    public function testExport()
    {
        //Arrange
        $expected = [
            'consoleLive' => false,
            'enableTool' => true,
            'queryLog' => false,
            'queryLogView' => null,
            'pointLabelLTrim' => false,
            'pointLabelRTrim' => false,
            'pointLabelNice' => false,
            'runInformation' => false,
            'clearScreen' => true,
            'presenter' => 1,
            'queryLogState' => null,
            'configItems' => null,
            'pointLabelTrim' => null,
        ];

        //Act
        $actual = $this->instance->export();

        //Assert
        $this->assertEquals($expected, $actual);
        $this->assertInternalType('array', $actual);
    }

    /**
     * @throws \Exception
     *
     * @covers ConfigHandler::enableTool()
     */
    public function testSetEnableTookWithBoolean()
    {
        //Arrange
        $expected = true;

        //Act
        $this->instance->enableTool(true);
        $actual = $this->instance->isEnableTool();

        //Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @runInSeparateProcess
     * @throws \Exception
     *
     * @covers ConfigHandler::enableTool()
     */
    public function testSetEnableTookWithString()
    {
        //Arrange
        $expected = true;

        //Act
        \putenv('FOO=true');
        $this->instance->enableTool('ENV:FOO');
        $actual = $this->instance->isEnableTool();

        //Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @throws \Exception
     *
     * @covers ConfigHandler::enableTool()
     * @todo mocking necessary
     */
    public function testSetEnableTookWithStringButNoEnvEntry()
    {
        //Arrange
        $this->expectException('Exception');
        $this->expectExceptionMessage('ENV possibly set up wrong. Tool could not be enabled. ENV:FOO');

        //Act
        $this->instance->enableTool('ENV:FOO');

        //Assert
    }

    /**
     * @throws \Exception
     *
     * @covers ConfigHandler::enableTool()
     */
    public function testSetEnableTookWithInvalidString()
    {
        //Arrange
        $this->expectException('Exception');
        $this->expectExceptionMessage('Config::DISABLE_TOOL value string \'foo:bar\' not supported! Check if ENV and value exists.');

        //Act
        $this->instance->enableTool('foo:bar');

        //Assert
    }

    /**
     * @throws \Exception
     *
     * @covers ConfigHandler::enableTool()
     */
    public function testSetEnableTookWithInvalidValue()
    {
        //Arrange
        $this->expectException('Exception');
        $this->expectExceptionMessage('Config::DISABLE_TOOL value \'array\' not supported!');

        //Act
        $this->instance->enableTool([]);

        //Assert
    }

    /**
     * @throws \Exception
     *
     * @covers ConfigHandler::setPresenter()
     */
    public function testIntegerPresenter()
    {
        //Arrange
        $expected = 1;

        //Act
        $this->instance->setPresenter(PresenterConfiguration::PRESENTER_CONSOLE_KEY);
        $actual = $this->instance->getPresenter();

        //Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @throws \Exception
     *
     * @covers ConfigHandler::setPresenter()
     */
    public function testStringPresenter()
    {
        //Arrange
        $expected = 1;

        //Act
        $this->instance->setPresenter('console');
        $actual = $this->instance->getPresenter();

        //Assert
        $this->assertEquals($expected, $actual);
    }

    /**
     * @throws \Exception
     *
     * @covers ConfigHandler::setPresenter()
     */
    public function testInvalidPresenter()
    {
        //Arrange
        $this->expectException('Exception');
        $this->expectExceptionMessage('Presenter \'Foo\' does not exists. Use: console or web.');

        //Act
        $this->instance->setPresenter('Foo');

        //Assert
    }

    protected function setUp()
    {
        parent::setUp();

        $this->instance = new ConfigHandler();
    }
}