<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

class InputRange
{
    private array $ranges = [];

    public function __construct(?InputRange $range = null)
    {
        if ($range !== null)
            foreach ($range->getNames() as $name)
                $this->setRange($name, $range->getRange($name));
    }

    /**
     * @return string[]
     */
    public function getNames(): array
    {
        return array_keys($this->ranges);
    }

    public function getRange(string $name): ?NumberRange
    {
        return $this->ranges[$name] ?? null;
    }

    public function setRange(string $name, NumberRange $range): void
    {
        $this->ranges[$name] = $range;
    }

    public function getCombinations(): int
    {
        $combinations = 1;
        foreach ($this->getNames() as $name)
        {
            $range = $this->getRange($name);
            if ($range->isValid())
                $combinations *= $range->getCombinations();
            else
                return 0;
        }
        return $combinations;
    }
}
