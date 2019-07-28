<?php

declare(strict_types=1);

namespace Performance\Lib\Holders;

/**
 * Class CalculateTotalHolder
 * @package Performance\Lib\Holders
 */
class CalculateTotalHolder
{
    /**
     * @var int
     */
    private $totalTime = 0;

    /**
     * @var int
     */
    private $totalMemory = 0;

    /**
     * @var int
     */
    private $totalMemoryPeak = 0;

    /**
     * CalculateTotalHolder constructor.
     * @param $totalTime
     * @param $totalMemory
     * @param $totalMemoryPeak
     */
    public function __construct(int $totalTime, int $totalMemory, int $totalMemoryPeak)
    {
        $this->totalTime = $totalTime;
        $this->totalMemory = $totalMemory;
        $this->totalMemoryPeak = $totalMemoryPeak;
    }

    /**
     * @return int
     */
    public function getTotalTime(): int
    {
        return $this->totalTime;
    }

    /**
     * @param int $totalTime
     * @return CalculateTotalHolder
     */
    public function setTotalTime(int $totalTime): CalculateTotalHolder
    {
        $this->totalTime = $totalTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalMemory(): int
    {
        return $this->totalMemory;
    }

    /**
     * @param int $totalMemory
     * @return CalculateTotalHolder
     */
    public function setTotalMemory(int $totalMemory): CalculateTotalHolder
    {
        $this->totalMemory = $totalMemory;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalMemoryPeak(): int
    {
        return $this->totalMemoryPeak;
    }

    /**
     * @param int $totalMemoryPeak
     * @return CalculateTotalHolder
     */
    public function setTotalMemoryPeak(int $totalMemoryPeak): CalculateTotalHolder
    {
        $this->totalMemoryPeak = $totalMemoryPeak;

        return $this;
    }
}