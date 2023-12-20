<?php

namespace Aoc\Y2023\Day20\Common;

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

class BroadcastModule extends Module
{
    public function __construct(string $name, string ...$connected)
    {
        parent::__construct($name, ...$connected);
    }


    /**
     * @param Pulse $pulse
     * @param int $pc
     * @return Pulse[]
     */
    public function execute(Pulse $pulse, int $pc): array
    {
        return $this->getPulses($pulse);
    }
}
