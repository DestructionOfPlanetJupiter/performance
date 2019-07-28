<?php

declare(strict_types=1);

namespace Performance\Lib\Holders;

use Performance\Lib\Handlers\ConfigHandler;

/**
 * Class InformationHolder
 * @package Performance\Lib\Holders
 */
class InformationHolder
{
    /**
     * @var ConfigHandler
     */
    protected $config;

    /**
     * @var string
     */
    protected $currentUser = '';

    /**
     * @var int
     */
    protected $currentProcessId = 0;

    /**
     * InformationHolder constructor.
     * @param ConfigHandler $config
     */
    public function __construct(ConfigHandler $config)
    {
        $this->config = $config;

        // Set information
        $this->activateConfig();
    }

    protected function activateConfig(): void
    {
        if ($this->config->isRunInformation()) {
            $this->setRunInformation();
        }
    }

    protected function setRunInformation():void
    {
        // Set current user
        $this->currentUser = get_current_user();

        // Set current process id
        $this->currentProcessId = (int)(getmypid());
    }

    /**
     * @return string
     */
    public function getCurrentUser(): string
    {
        return $this->currentUser;
    }

    /**
     * @return int
     */
    public function getCurrentProcessId(): int
    {
        return $this->currentProcessId;
    }
}
