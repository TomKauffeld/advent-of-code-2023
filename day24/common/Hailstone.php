<?php

namespace Aoc\Y2023\Day24\Common;

class Hailstone
{
    private Vector3 $position;
    private Vector3 $velocity;

    public function __construct(Vector3 $position, Vector3 $velocity)
    {
        $this->position = $position;
        $this->velocity = $velocity;
    }

    public function getPosition(): Vector3
    {
        return $this->position;
    }

    public function getVelocity(): Vector3
    {
        return $this->velocity;
    }


    private function isZero(float $v): bool
    {
        return abs($v) < 0.0001;
    }


    private ?float $a = null;
    public function getA(): float
    {
        if ($this->a === null)
            $this->a = $this->getVelocity()->getY() / $this->getVelocity()->getX();
        return $this->a;
    }

    private ?float $b = null;
    public function getB(): float
    {
        if ($this->b === null)
            $this->b = $this->getPosition()->getY() - $this->getA() * $this->getPosition()->getX();
        return $this->b;
    }

    public function intersects2D(Hailstone $other): ?Vector3
    {
        $d1 = $other->getB() - $this->getB();
        $d2 = $this->getA() - $other->getA();
        if ($this->isZero($d2))
            return null;
        $x = $d1 / $d2;
        if ($this->getVelocity()->getX() > 0 === $x < $this->getPosition()->getX())
            return null;
        if ($other->getVelocity()->getX() > 0 === $x < $other->getPosition()->getX())
            return null;
        $y = $this->getA() * $x + $this->getB();
        return new Vector3(
            $x,
            $y,
            0,
        );
    }

}