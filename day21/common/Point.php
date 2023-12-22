<?php

namespace Aoc\Y2023\Day21\Common;

class Point
{
    private int $x;
    private int $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function equals(Point $point): bool
    {
        return $this->getX() === $point->getX() && $this->getY() === $point->getY();
    }

    public function distance(Point $other): int
    {
        return abs($this->getX() - $other->getX()) + abs($this->getY() - $other->getY());
    }

    public function hash(): int
    {
        return $this->getX() + $this->getY() * 1234567890;
    }
}