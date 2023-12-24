<?php

namespace Aoc\Y2023\Day24\Common;

class Vector3
{
    private float $x;
    private float $y;
    private float $z;

    public function __construct(float $x, float $y, float $z)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function getZ(): float
    {
        return $this->z;
    }

    public function getMagnitude(): float
    {
        return sqrt(
            $this->getX() * $this->getX() +
            $this->getY() * $this->getY() +
            $this->getZ() * $this->getZ()
        );
    }

    public function cross(self $other): self
    {
        return new self(
            $this->getY() * $other->getZ() - $this->getZ() * $other->getY(),
            $this->getZ() * $other->getX() - $this->getX() * $other->getZ(),
            $this->getX() * $other->getY() - $this->getY() * $other->getX(),
        );
    }

    public function minus(self $other): self
    {
        return new self(
            $this->getX() - $other->getX(),
            $this->getY() - $other->getY(),
            $this->getZ() - $other->getZ(),
        );
    }

    public function add(self $other): self
    {
        return new self(
            $this->getX() + $other->getX(),
            $this->getY() + $other->getY(),
            $this->getZ() + $other->getZ(),
        );
    }

    public function multiply(float $value): self
    {
        return new self(
            $this->getX() * $value,
            $this->getY() * $value,
            $this->getZ() * $value,
        );
    }
}