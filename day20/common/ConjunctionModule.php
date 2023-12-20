<?php

namespace Aoc\Y2023\Day20\Common;

use RuntimeException;

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

class ConjunctionModule extends Module implements KnowsSources
{
    /**  @var Pulse[] */
    private array $sources = [];

    public function __construct(string $name, string ...$connected)
    {
        parent::__construct($name, ...$connected);
    }

    public function setSources(string ...$sources): void
    {
        $this->sources = [];
        foreach ($sources as $source)
            $this->sources[$source] = Pulse::LowPulse($source, $this->getName());
    }

    /** @return string[] */
    public function getSources(): array
    {
        return array_keys($this->sources);
    }

    protected function updateSource(Pulse $pulse): void
    {
        if (!isset($this->sources[$pulse->getSource()]))
            throw new RuntimeException('Invalid source: "' . $pulse->getSource() . '"');
        $this->sources[$pulse->getSource()] = $pulse;
    }

    public function isAllHighPulses(): bool
    {
        foreach ($this->sources as $pulse)
            if (!$pulse->isHighPulse())
                return false;
        return true;
    }

    public function isAllLowPulses(): bool
    {
        foreach ($this->sources as $pulse)
            if (!$pulse->isLowPulse())
                return false;
        return true;
    }


    /**
     * @return Pulse[]
     */
    public function execute(Pulse $pulse, int $pc): array
    {
        $this->updateSource($pulse);
        if ($this->isAllHighPulses()) {
            return $this->getLowPulses();
        }
        return $this->getHighPulses();
    }

    public function printState(): void
    {
        foreach ($this->sources as $pulse)
            print ($pulse->isHighPulse() ? 'H' : 'L');
        print("\n");
    }
}
