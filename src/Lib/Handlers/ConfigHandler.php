<?php

declare(strict_types=1);

namespace Performance\Lib\Handlers;

use Exception;
use InvalidArgumentException;
use Performance\Lib\Configuration\PresenterConfiguration;
use Performance\Lib\Interfaces\ExportInterface;

/**
 * Class ConfigHandler
 * @package Performance\Lib\Handlers
 */
class ConfigHandler implements ExportInterface
{
    // Config items
    const OPTIONS_SHORT = 'l::';
    const OPTIONS_LONG = 'live';
    const OPTIONS_LIVE = 'l';
    /**
     * @var bool
     */
    protected $consoleLive = false;

    /**
     * @var bool
     */
    protected $enableTool = true;

    /**
     * @var bool
     */
    protected $queryLog = false;

    /**
     * @var
     */
    protected $queryLogView;

    /**
     * @var bool
     */
    protected $pointLabelLTrim = false;

    /**
     * @var bool
     */
    protected $pointLabelRTrim = false;

    /**
     * @var bool
     */
    protected $pointLabelNice = false;

    /**
     * @var bool
     */
    protected $runInformation = false;

    /**
     * @var bool
     */
    protected $clearScreen = true;

    /**
     * @var
     */
    protected $presenter;

    /**
     * Hold state of the query log
     * null = not set
     * false = config is false
     * true = query log is set
     */
    protected $queryLogState;

    /**
     * @var
     */
    protected $configItems;

    /**
     * @var
     */
    protected $pointLabelTrim;

    /**
     * ConfigHandler constructor.
     */
    public function __construct()
    {
        // Set default
        $this->setDefaultConsoleLive();
        $this->setDefaultPresenter();
    }

    protected function setDefaultConsoleLive()
    {
        $options = \getopt(self::OPTIONS_SHORT, [self::OPTIONS_LONG]);

        // Set live option
        if (isset($options[self::OPTIONS_LIVE]) || isset($options[self::OPTIONS_LONG]))
            $this->consoleLive = true;
    }

    protected function setDefaultPresenter()
    {
        $presenter = PresenterConfiguration::PRESENTER_WEB_KEY;

        if (\php_sapi_name() === PresenterConfiguration::PRESENTER_CLI_NAME) {
            $presenter = PresenterConfiguration::PRESENTER_CONSOLE_KEY;
        }

        $this->setPresenter($presenter);

    }

    /**
     * Simple export function
     */
    public function export(): array
    {
        return \get_object_vars($this);
    }

    /**
     * @return array
     */
    public function getAllItemNames()
    {
        return \array_keys($this->configItems);
    }

    /**
     * @return bool
     */
    public function isConsoleLive()
    {
        return $this->consoleLive;
    }

    /**
     * @param $status
     */
    public function setConsoleLive($status)
    {
        $this->consoleLive = $status;
    }

    /**
     * @return bool
     */
    public function isEnableTool()
    {
        return $this->enableTool;
    }

    /**
     * @param $value
     */
    public function setEnableTool(bool $value)
    {
        $this->enableTool = $value;
    }

    /**
     * @param $value
     * @throws Exception
     */
    public function enableTool($value)
    {
        if (\is_bool($value)) {
            $this->setEnableTool($value);
        } elseif (\is_string($value)) {
            $split = \explode(':', $value);

            // Determinable stat on ENV
            if (isset($split[1]) && $split[0] === 'ENV' && \function_exists('env')) {
                $enabled = (bool)\env($split[1]);

                if ($enabled === false) {
                    throw new Exception('ENV possibly set up wrong. Tool could not be enabled. ' . $value);
                }

                $this->setEnableTool($enabled);
            } else {
                {
                    \print_r($split);
                    throw new Exception("Config::DISABLE_TOOL value string '" . $value . "' not supported! Check if ENV and value exists.");
                }
            }
        } else {
            throw new Exception("Config::DISABLE_TOOL value '" . \gettype($value) . "' not supported!");
        }
    }

    /**
     * @return bool
     */
    public function isQueryLog()
    {
        return $this->queryLog;
    }

    /**
     * @param bool $queryLog
     * @param null $viewOption
     */
    public function setQueryLog($queryLog, $viewOption = null)
    {
        $this->queryLog = $queryLog;
        $this->setQueryLogView($viewOption);
    }

    /**
     * @return mixed
     */
    public function getQueryLogView()
    {
        return $this->queryLogView;
    }

    /**
     * @param mixed $queryLogView
     */
    protected function setQueryLogView($queryLogView = null)
    {
        if ($queryLogView === 'resume' || !$queryLogView) {
            $this->queryLogView = 'resume';
        } elseif ($queryLogView == 'full') {
            $this->queryLogView = $queryLogView;
        } else {
            throw new InvalidArgumentException("Query log view '" . $queryLogView . "' does not exists, use: 'resume' or 'full'");
        }
    }


    /**
     * @return bool
     */
    public function isPointLabelTrim()
    {
        return $this->pointLabelTrim;
    }

    /**
     * @return mixed
     */
    public function getPointLabelLTrim()
    {
        return $this->pointLabelLTrim;
    }

    /**
     * @param $status
     */
    public function setPointLabelLTrim($status)
    {
        $this->pointLabelLTrim = $status;
    }

    /**
     * @return mixed
     */
    public function getPointLabelRTrim()
    {
        return $this->pointLabelRTrim;
    }

    /**
     * @param $status
     */
    public function setPointLabelRTrim($status)
    {
        $this->pointLabelRTrim = $status;
    }

    /**
     * @return mixed
     */
    public function getPresenter()
    {
        return $this->presenter;
    }

    /**
     * @param $mixed
     */
    public function setPresenter($mixed)
    {
        if (\is_int($mixed))
            $this->presenter = $mixed;
        else
            if ($mixed === PresenterConfiguration::PRESENTER_CONSOLE_VALUE) {
                $this->presenter = PresenterConfiguration::PRESENTER_CONSOLE_KEY;
            } elseif ($mixed === PresenterConfiguration::PRESENTER_WEB_VALUE) {
                $this->presenter = PresenterConfiguration::PRESENTER_WEB_KEY;
            } else {
                throw new InvalidArgumentException("Presenter '" . $mixed . "' does not exists. Use: console or web.");
            }
    }

    /**
     * @return bool
     */
    public function isPointLabelNice()
    {
        return $this->pointLabelNice;
    }

    /**
     * @param bool $pointLabelNice
     */
    public function setPointLabelNice($pointLabelNice)
    {
        $this->pointLabelNice = (bool)$pointLabelNice;
    }

    /**
     * @return bool
     */
    public function isRunInformation()
    {
        return $this->runInformation;
    }

    /**
     * Set run information
     * @param bool $status
     */
    public function setRunInformation($status)
    {
        $this->runInformation = (bool)$status;
    }

    /**
     * @return bool
     */
    public function isClearScreen()
    {
        return $this->clearScreen;
    }

    /**
     * @param bool $clearScreen
     */
    public function setClearScreen($clearScreen)
    {
        $this->clearScreen = (bool)$clearScreen;
    }

    /**
     * @return mixed
     */
    public function getQueryLogState()
    {
        return $this->queryLogState;
    }

    /**
     * @param mixed $queryLogState
     */
    public function setQueryLogState($queryLogState)
    {
        $this->queryLogState = $queryLogState;
    }

    /**
     * @return mixed
     */
    public function getConfigItems()
    {
        return $this->configItems;
    }

    /**
     * @param mixed $configItems
     */
    public function setConfigItems($configItems)
    {
        $this->configItems = $configItems;
    }
}
