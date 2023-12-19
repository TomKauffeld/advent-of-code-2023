<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

class NumberRange
{
    private int $min;
    private int $max;

    public function __construct(int $min = 0, int $max = 4000)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function getMin(): int
    {
        return $this->min;
    }

    public function getMax(): int
    {
        return $this->max;
    }

    public function getWithMin(int $min): self
    {
        return new self(max($min, $this->getMin()), $this->getMax());
    }

    public function getWithMax(int $max): self
    {
        return new self($this->getMin(), min($max, $this->getMax()));
    }

    public function isValid(): bool
    {
        return $this->getMax() >= $this->getMin();
    }

    public function getCombinations(): int
    {
        return $this->getMax() - $this->getMin() + 1;
    }
}
