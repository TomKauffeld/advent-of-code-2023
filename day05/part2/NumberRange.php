<?php

class NumberRange
{
    private int $start;
    private int $length;

    public function __construct(int $start, int $length)
    {
        $this->start = $start;
        $this->length = $length;
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getEnd(): int
    {
        return $this->getStart() + $this->getLength();
    }

    public function getLastNumber(): ?int
    {
        if ($this->getLength() > 0)
            return $this->getEnd() - 1;
        return null;
    }


}
