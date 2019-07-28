<?php

namespace tests\src\Presenters\Calculate;

use Performance\Lib\Handlers\ConfigHandler;
use Performance\Lib\Presenters\Formatter;
use PHPUnit\Framework\TestCase;

/**
 * Class FormatterTest
 * @package tests\src\Lib\Handlers
 */
class FormatterTest extends TestCase
{
    /**
     * @var Formatter
     */
    protected $instance;

    protected function setUp(): void
    {
        $this->instance = new Formatter(new ConfigHandler());
    }


}