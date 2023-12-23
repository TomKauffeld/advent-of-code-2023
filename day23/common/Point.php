<?php

namespace Aoc\Y2023\Day23\Common;

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

    public function left(): self
    {
        return new self($this->getX() - 1, $this->getY());
    }

    public function right(): self
    {
        return new self($this->getX() + 1, $this->getY());
    }

    public function up(): self
    {
        return new self($this->getX(), $this->getY() - 1);
    }

    public function down(): self
    {
        return new self($this->getX(), $this->getY() + 1);
    }

    public function equals(Point $other): bool
    {
        return $this->getX() === $other->getX() && $this->getY() === $other->getY();
    }

    public function hash(): int
    {
        return $this->getX() + $this->getY() * 123456789;
    }
}