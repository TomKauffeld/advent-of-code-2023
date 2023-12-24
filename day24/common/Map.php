<?php

namespace Aoc\Y2023\Day24\Common;

use InvalidArgumentException;
use RuntimeException;

class Map
{
    /** @var Hailstone[] */
    private array $hailstones;

    public function __construct(array $hailstones)
    {
        $this->hailstones = $hailstones;
    }

    /** @return Vector3[] */
    public function getIntersections(): array
    {
        $intersections = [];
        $count = count($this->hailstones);
        for ($i = 0; $i < $count; ++$i)
        {
            for ($j = $i + 1; $j < $count; ++$j)
            {
                $intersection = $this->hailstones[$i]->intersects2D($this->hailstones[$j]);
                if ($intersection !== null) {
                    $intersections[] = $intersection;
                }
            }
        }
        return $intersections;
    }
}