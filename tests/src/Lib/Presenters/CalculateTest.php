<?php

namespace tests\src\Presenters\Calculate;

use Performance\Lib\Holders\CalculateTotalHolder;
use Performance\Lib\Presenters\Calculate;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

/**
 * Class CalculateTest
 * @package tests\src\Lib\Handlers
 */
class CalculateTest extends TestCase
{
    protected const POINT_DIFFERENCE = 'pointDifference';
    protected const TOTAL = 'total';
    protected const EXPECTED = 'expected';

    /**
     * @var Calculate
     */
    protected $instance;

    /**
     * @test
     *
     * @param int $pointDifference
     * @param int $total
     * @param int $expected
     *
     * @dataProvider calculatePercentageDataProvider
     */
    public function runWithEmptyResult(int $pointDifference, int $total, int $expected)
    {
        //Arrange

        //Act
        $result = $this->instance->calculatePercentage($pointDifference, $total);

        //Assert
        $this->assertEquals($expected, $result);
    }

    public function calculatePercentageDataProvider(): \Generator
    {
        yield 'empty params' => [self::POINT_DIFFERENCE => 0, self::TOTAL => 0, self::EXPECTED => 0];
        yield 'empty total' => [self::POINT_DIFFERENCE => 10, self::TOTAL => 0, self::EXPECTED => 0];
        yield 'empty pointDifference' => [self::POINT_DIFFERENCE => 0, self::TOTAL => 10, self::EXPECTED => 0];
        yield 'pointDifference and total with values' => [
            self::POINT_DIFFERENCE => 10,
            self::TOTAL => 10,
            self::EXPECTED => 100
        ];
    }

    /**
     * @test
     */
    public function totalTimeAndMemoryFromEmptyPointStack()
    {
        //Arrange
        $expected = new CalculateTotalHolder(0, 0, Argument::any());

        //Act
        $result = $this->instance->totalTimeAndMemory([]);

        //Assert
        $this->assertEquals($expected, $result);
    }

    protected function setUp(): void
    {
        $this->instance = new Calculate();
    }
}