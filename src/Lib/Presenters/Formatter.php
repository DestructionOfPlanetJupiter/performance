<?php

declare(strict_types=1);

namespace Performance\Lib\Presenters;

use function array_key_exists;
use function array_search;
use function floor;
use function is_numeric;
use function ksort;
use Performance\Lib\Handlers\ConfigHandler;
use Performance\Lib\Holders\QueryLineHolder;
use Performance\Lib\Point;

use function pow;
use function sprintf;
use function str_repeat;
use function strlen;
use function strpos;

/**
 * Class Formatter
 * @package Performance\Lib\Presenters
 */
class Formatter
{

    private const COUNT = 'count';
    private const TIME = 'time';
    private const MICROSECONDS = 1000000;
    private const MILLISECONDS = 1000;

    protected $config;

    /**
     * Formatter constructor.
     * @param ConfigHandler $config
     */
    public function __construct(ConfigHandler $config)
    {
        $this->config = $config;
    }

    protected static function unitByMicroTime(float $microTime, string $unit): string
    {
        if ($unit === "auto") {
            if ($microTime > 1) {
                $unit = 's';
            } elseif ($microTime > 0.001) {
                $unit = 'ms';
            } else {
                $unit = 'μs';
            }
        }
        return $unit;
    }

    /**
     * @param $microTime
     * @param string $unit
     * @param int $decimals
     * @return float
     *
     * @throws FormatterException
     */
    public function timeToHuman(float $microTime, string $unit = 'auto', int $decimals = 2): float
    {
        $unit = self::unitByMicroTime($microTime, $unit);

        switch ($unit) {
            case 'μs':
                $result = round($microTime * self::MICROSECONDS, $decimals) . ' ' . $unit;
                break;
            case 'ms':
                $result = round($microTime * self::MILLISECONDS, $decimals) . ' ' . $unit;
                break;
            case 's':
                $result = round($microTime * 1, $decimals) . '  ' . $unit;
                break;
            default:
                throw new FormatterException($this, 'Performance format ' . $unit . ' not exist');
        }

        return (float)$result;
    }

    /**
     * Creatis to cam-gists/memoryuse.php !!
     *
     * @param $bytes
     * @param string $unit
     * @param int $decimals
     * @return string
     */
    public function memoryToHuman($bytes, $unit = "", $decimals = 2): string
    {
        if ($bytes <= 0) {
            return '0.00 KB';
        }

        $units = [
            'B' => 0,
            'KB' => 1,
            'MB' => 2,
            'GB' => 3,
            'TB' => 4,
            'PB' => 5,
            'EB' => 6,
            'ZB' => 7,
            'YB' => 8
        ];

        $value = 0;

        if ($bytes > 0) {
            // Generate automatic prefix by bytes
            // If wrong prefix given
            if (!array_key_exists($unit, $units)) {
                $pow = floor(log($bytes) / log(1024));
                $unit = array_search($pow, $units);
            }

            // Calculate byte value by prefix
            $value = ($bytes / pow(1024, floor($units[ $unit ])));
        }

        // If decimals is not numeric or decimals is less than 0
        if (!is_numeric($decimals) || $decimals < 0) {
            $decimals = 2;
        }

        // Format output
        return sprintf('%.' . $decimals . 'f ' . $unit, $value);
    }

    /**
     * @param string $input
     * @param $pad_length
     * @param string $pad_string
     * @return string
     *
     * @todo Fix problem 'μs'
     *
     */
    public function stringPad(string $input, int $pad_length, string $pad_string = ' '): string
    {
        $count = \strlen($input);

        // Fix μ issues
        if (strpos($input, 'μ')) {
            --$count;
        }

        $space = $pad_length - $count;

        return str_repeat($pad_string, $space) . $input;
    }

    /**
     * @param Point $point
     * @return array
     */
    public function createPointQueryLogLineList(Point $point): array
    {
        $lineArray = [];

        if (!$point->getQueryLog()) {
            return $lineArray;
        }

        if ($this->config->getQueryLogView() === 'resume') {
            $buildLineList = [];

            foreach ($point->getQueryLog() as $queryLogHolder) {
                $type = $queryLogHolder->getQueryType();
                if (!isset($buildLineList[ $type ])) {
                    $buildLineList[ $type ][ self::COUNT ] = 1;
                    $buildLineList[ $type ][ self::TIME ] = $queryLogHolder->getTime();
                    continue;
                }

                ++$buildLineList[ $type ][ self::COUNT ];
                $buildLineList[ $type ][ self::TIME ] = $buildLineList[ $type ][ self::TIME ] + $queryLogHolder->getTime();
            }

            ksort($buildLineList);

            foreach ($buildLineList as $key => $item) {
                $queryLineHolder = new QueryLineHolder();
                $queryLineHolder->setLine('Database query ' . $key . ' ' . $item[ self::COUNT ] . 'x');
                $queryLineHolder->setTime((int)($item[ self::TIME ] ?? 0));
                $lineArray[] = $queryLineHolder;
            }
        }

        // View type full
        if ($this->config->getQueryLogView() === 'full') {
            foreach ($point->getQueryLog() as $queryLogHolder) {
                $queryLineHolder = new QueryLineHolder();
                $queryLineHolder->setLine((string)($queryLogHolder->getQuery()));
                $queryLineHolder->setTime((int)($queryLogHolder->getTime()));
                $lineArray[] = $queryLineHolder;
            }
        }

        return $lineArray;
    }

    /**
     * @param $string
     * @param $width
     * @return string
     */
    public function formatStringWidth($string, $width): string
    {
        return ((strlen($string) > $width) ? substr($string, 0, $width - 3) . '...' : $string);
    }
}
