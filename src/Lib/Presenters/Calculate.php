<?php

declare(strict_types=1);

namespace Performance\Lib\Presenters;

use function array_slice;
use function memory_get_peak_usage;
use Performance\Lib\Holders\CalculateTotalHolder;
use Performance\Lib\Point;
use function round;

/**
 * Class Calculate
 * @package Performance\Lib\Presenters
 */
class Calculate
{
    /**
     * Calculate total memory
     *
     * @param Point[] $pointStack
     * @return CalculateTotalHolder
     */
    public function totalTimeAndMemory($pointStack): CalculateTotalHolder
    {
        $max_time = 0;
        $max_memory = 0;

        foreach (array_slice($pointStack, 2) as $point) {
            $max_time += $point->getDifferenceTime();
            $max_memory += $point->getDifferenceMemory();
        }

        return new CalculateTotalHolder($max_time, $max_memory, memory_get_peak_usage(true));
    }

    /**
     * Calculate percentage
     *
     * @param int $pointDifference
     * @param int $total
     * @return int
     */
    public function calculatePercentage(int $pointDifference, int $total): int
    {
        $upCount = 1000000;
        $percentage = 0;

        if ($pointDifference > 0 && $total > 0) {
            $percentage = round((100 * $pointDifference * $upCount) / ($total * $upCount));
        }

        return (int)$percentage;
    }
}
