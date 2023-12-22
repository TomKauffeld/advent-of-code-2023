<?php

namespace Aoc\Y2023\Day21\Common;

class Map2 extends Map
{
    private array $explored = [];
    private array $distanceCache = [];

    public function __construct(Map $map)
    {
        parent::__construct($map->getData());
        $this->setWrap(true);
    }

    public function createExploreMap(int $steps, Point $startingLocation): void
    {
        $todo = new PointsList();
        $todo->push($startingLocation);
        $distance = [];
        $stepsMod2 = $steps % 2;
        $distance[$startingLocation->hash()] = 0;
        $this->setWrap(false);
        while (($point = $todo->pop()) !== null)
        {
            if (isset($this->explored[$point->getY()][$point->getX()]))
                continue;
            $d = $distance[$point->hash()];

            if (!isset($this->explored[$point->getY()]))
                $this->explored[$point->getY()] = [];

            $this->explored[$point->getY()][$point->getX()] = $d % 2 === $stepsMod2;

            foreach ($this->getNeighbors($point->getX(), $point->getY()) as $neighbor)
            {
                if (isset($this->explored[$neighbor->getY()][$neighbor->getX()]))
                    continue;
                $distance[$neighbor->hash()] = $d + 1;
                $todo->push($neighbor);
            }
        }
        $this->setWrap(true);
    }


    public function countReachableFrom(int $steps, Point $startingPoint, bool $inverse = false): int
    {
        $sum = 0;
        $this->setWrap(false);

        foreach ($this->explored as $y => $row)
        {
            foreach ($row as $x => $col)
            {
                if ($col === $inverse)
                    continue;
                if ($startingPoint->distance(new Point($x, $y)) > $steps + 1)
                    continue;
                $distance = $this->getDistance($startingPoint, new Point($x, $y));
                if ($distance > $steps + 1)
                    continue;
                ++$sum;
            }
        }
        $this->setWrap(true);
        return $sum;
    }

    private function getDistance(Point $a, Point $b): ?int
    {
        $aHash = $a->hash();
        $bHash = $b->hash();
        if (isset($this->distanceCache[$aHash][$bHash]))
            return $this->distanceCache[$aHash][$bHash];
        $path = $this->getPath($a, $b);
        if ($path === null)
            $distance = null;
        else
            $distance = count($path);
        $this->distanceCache[$aHash][$bHash] = $distance;
        $this->distanceCache[$bHash][$aHash] = $distance;
        return $distance;
    }

    public function print(PointsList $list = null): void
    {
        $list = $list ?? new PointsList();
        for ($y = 0; $y < $this->getHeight(); ++$y)
        {
            for ($x = 0; $x < $this->getWidth(); ++$x)
            {
                if ($list->includes(new Point($x, $y)))
                    print('X');
                elseif (isset($this->explored[$y][$x]) && $this->explored[$y][$x])
                    print('O');
                elseif ($this->isRock($x, $y))
                    print('#');
                elseif ($this->isGarden($x, $y))
                    print('.');
                else
                    print(' ');
            }
            print("\n");
        }
    }

    public function printExplore(bool $inverse = false): void
    {
        for ($y = 0; $y < $this->getHeight(); ++$y)
        {
            for ($x = 0; $x < $this->getWidth(); ++$x)
            {
                if (isset($this->explored[$y][$x]) && $this->explored[$y][$x] !== $inverse)
                    print('O');
                elseif ($this->isRock($x, $y))
                    print('#');
                elseif ($this->isGarden($x, $y))
                    print('.');
                else
                    print(' ');
            }
            print("\n");
        }
    }

    public function countExplored(bool $inverse = false): int
    {
        $sum = 0;
        foreach ($this->explored as $row)
            foreach ($row as $col)
                if ($col !== $inverse)
                    ++$sum;
        return $sum;
    }

}