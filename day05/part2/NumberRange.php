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

    public function includes(int $number): bool
    {
        return $number >= $this->getStart() && $number < $this->getEnd();
    }

    public function intersects(NumberRange $other): bool
    {
        return $other->getStart() < $this->getEnd()
            && $other->getEnd() > $this->getStart();
    }

    public function combine(NumberRange $other): NumberRange
    {
        if (!$this->intersects($other)) {
            throw new RuntimeException('cannot combine ranges');
        }
        $start = min($this->getStart(), $other->getStart());
        $end = max($this->getEnd(), $other->getEnd());
        return new NumberRange($start, $end - $start);
    }

    public function getIntersection(NumberRange $other): ?NumberRange
    {
        if (!$this->intersects($other)) {
            return null;
        }
        $start = max($this->getStart(), $other->getStart());
        $end = min($this->getEnd(), $other->getEnd());
        return new NumberRange($start, $end - $start);
    }
}
