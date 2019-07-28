<?php

declare(strict_types=1);

namespace Performance\Lib\Holders;

/**
 * Class QueryLineHolder
 * @package Performance\Lib\Holders
 */
class QueryLineHolder
{

    /**
     * @var string
     */
    protected $line = '';

    /**
     * @var string
     */
    protected $time = '';

    /**
     * @return string
     */
    public function getLine(): string
    {
        return $this->line;
    }

    /**
     * @param string $line
     * @return QueryLineHolder
     */
    public function setLine(string $line): QueryLineHolder
    {
        $this->line = $line;

        return $this;
    }

    /**
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }

    /**
     * @param int $time
     * @return QueryLineHolder
     */
    public function setTime(int $time): QueryLineHolder
    {
        $this->time = number_format($time, 2, '.', '');

        return $this;
    }
}
