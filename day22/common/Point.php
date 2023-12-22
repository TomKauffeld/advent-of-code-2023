<?php

namespace Aoc\Y2023\Day22\Common;

class Point
{
    private int $x;
    private int $y;
    private int $z;

    public function __construct(int $x, int $y, int $z)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getZ(): int
    {
        return $this->z;
    }

    public function equals(Point $other): bool
    {
        return $this->getX() === $other->getX() && $this->getY() === $other->getY() && $this->getZ() === $other->getZ();
    }
}