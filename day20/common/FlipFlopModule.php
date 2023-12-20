<?php

namespace Aoc\Y2023\Day20\Common;

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

class FlipFlopModule extends Module
{
    private bool $state;

    public function __construct(string $name, string ...$connected)
    {
        parent::__construct($name, ...$connected);
        $this->state = false;
    }

    public function getState(): bool
    {
        return $this->state;
    }

    public function setState(bool $state): bool
    {
        $this->state = $state;
        return $this->getState();
    }

    public function flipState(): bool
    {
        return $this->setState(!$this->getState());
    }


    /**
     * @param Pulse $pulse
     * @param int $pc
     * @return Pulse[]
     */
    public function execute(Pulse $pulse, int $pc): array
    {
        if ($pulse->isLowPulse()) {
            $this->flipState();
            if ($this->getState())
                return $this->getHighPulses();
            return $this->getLowPulses();
        }
        return [];
    }
}
