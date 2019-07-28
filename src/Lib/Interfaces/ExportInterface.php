<?php

declare(strict_types=1);

namespace Performance\Lib\Interfaces;

/**
 * Interface ExportInterface
 * @package Performance\Lib\Interfaces
 */
interface ExportInterface
{
    /**
     * Exports data to presenter
     *
     * @return array
     */
    public function export(): array;

}