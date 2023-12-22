<?php

namespace Aoc\Y2023\Day22\Common;

class Brick
{
    private Point $start;
    private Point $end;

    public function __construct(Point $start, Point $end)
    {
        $this->start = new Point(
            min($start->getX(), $end->getX()),
            min($start->getY(), $end->getY()),
            min($start->getZ(), $end->getZ()),
        );
        $this->end = new Point(
            max($start->getX(), $end->getX()),
            max($start->getY(), $end->getY()),
            max($start->getZ(), $end->getZ()),
        );
    }

    public function getStart(): Point
    {
        return $this->start;
    }

    public function getEnd(): Point
    {
        return $this->end;
    }

    public function isPointInside(Point $point): bool
    {
         return $this->getStart()->getX() <= $point->getX() &&
            $point->getX() <= $this->getEnd()->getX() &&
            $this->getStart()->getY() <= $point->getY() &&
            $point->getY() <= $this->getEnd()->getY() &&
            $this->getStart()->getZ() <= $point->getZ() &&
            $point->getZ() <= $this->getEnd()->getZ();
    }

    public function intersects(Brick $other): bool
    {
        return $this->getStart()->getX() <= $other->getEnd()->getX() &&
            $other->getStart()->getX() <= $this->getEnd()->getX() &&
            $this->getStart()->getY() <= $other->getEnd()->getY() &&
            $other->getStart()->getY() <= $this->getEnd()->getY() &&
            $this->getStart()->getZ() <= $other->getEnd()->getZ() &&
            $other->getStart()->getZ() <= $this->getEnd()->getZ();
    }

    public function isOtherBrickAbove(Brick $other): bool
    {
        return $this->getStart()->getX() <= $other->getEnd()->getX() &&
            $other->getStart()->getX() <= $this->getEnd()->getX() &&
            $this->getStart()->getZ() <= $other->getEnd()->getZ() &&
            $other->getStart()->getZ() <= $this->getEnd()->getZ() &&
            $this->getEnd()->getY() + 1 === $other->getStart()->getY();
    }

    public function isOtherBrickBelow(Brick $other): bool
    {
        return $other->isOtherBrickAbove($this);
    }

    public function equals(Brick $other): bool
    {
        return $this->getStart()->equals($other->getStart()) && $this->getEnd()->equals($other->getEnd());
    }
}