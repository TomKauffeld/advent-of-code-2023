<?php

namespace Aoc\Y2023\Day21\Common;

class PointsList
{
    /** @var Point[] */
    private array $list = [];


    public function count(): int
    {
        return count($this->list);
    }

    public function push(Point $point)
    {
        if (!$this->includes($point))
            $this->list[] = $point;
    }

    public function includes(Point $point): bool
    {
        foreach ($this->list as $p)
            if ($p->equals($point))
                return true;
        return false;
    }

    public function pop(): ?Point
    {
        return array_pop($this->list);
    }

    /**
     * @return Point[]
     */
    public function toArray(): array
    {
        return $this->list;
    }
}