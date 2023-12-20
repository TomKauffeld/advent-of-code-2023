<?php

namespace Aoc\Y2023\Day20\Common;

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

abstract class Module
{
    private string $name;
    /** @var string[] */
    private array $connected;

    public function __construct(string $name, string ...$connected)
    {
        $this->name = $name;
        $this->connected = $connected;
    }

    /** @return string[] */
    public function getConnected(): array
    {
        return $this->connected;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /** @return Pulse[] */
    protected function getLowPulses(): array
    {
        return array_map(function (string $target): Pulse {
            return Pulse::LowPulse($this->getName(), $target);
        }, $this->connected);
    }

    /** @return Pulse[] */
    protected function getHighPulses(): array
    {
        return array_map(function (string $target): Pulse {
            return Pulse::HighPulse($this->getName(), $target);
        }, $this->connected);
    }

    /** @return Pulse[] */
    protected function getPulses(Pulse $original): array
    {
        return array_map(function (string $target) use ($original): Pulse {
            return Pulse::NewPulse($this->getName(), $target, $original);
        }, $this->connected);
    }

    /**
     * @return Pulse[]
     */
    public abstract function execute(Pulse $pulse, int $pc): array;
}
