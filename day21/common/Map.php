<?php

namespace Aoc\Y2023\Day21\Common;

use InvalidArgumentException;
use OutOfBoundsException;

class Map
{
    private bool $wraps = false;
    private array $data = [];
    private int $width;
    private int $height;

    public function getData(): array
    {
        return $this->data;
    }

    public function __construct(array $data)
    {
        $this->height = count($data);
        $this->width = count($data[0]);
        for($y = 0; $y < $this->height; ++$y)
        {
            if (!isset($data[$y]) || !is_array($data[$y]) || $this->width !== count($data[$y]))
                throw new InvalidArgumentException("Invalid MAP at $y");
            $this->data[$y] = [];
            for ($x = 0; $x < $this->width; ++$x)
            {
                if (!isset($data[$y][$x]) || !is_bool($data[$y][$x]))
                    throw new InvalidArgumentException("Invalid MAP at $y - $x");
                $this->data[$y][$x] = $data[$y][$x];
            }
        }
    }

    public function doesWrap(): bool
    {
        return $this->wraps;
    }

    public function setWrap(bool $wrap): void
    {
        $this->wraps = $wrap;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function isRock(int $x, int $y): bool
    {
        if ($this->doesWrap())
        {
            while ($x >= $this->getWidth())
                $x -= $this->getWidth();
            while ($y >= $this->getHeight())
                $y -= $this->getHeight();
            while ($x < 0)
                $x += $this->getWidth();
            while ($y < 0)
                $y += $this->getHeight();
        }
        if ($x < 0 || $x >= $this->getWidth() || $y < 0 || $y >= $this->getHeight())
            throw new OutOfBoundsException("invalid coordinates $x $y");
        return $this->data[$y][$x];
    }

    public function isGarden(int $x, int $y): bool
    {
        return !$this->isRock($x, $y);
    }

    /**
     * @param int $x
     * @param int $y
     * @return Point[]
     */
    public function getNeighbors(int $x, int $y): array
    {
        $positions = [];

        if ($this->doesWrap() && false)
        {
            if ($this->isGarden($x - 1, $y))
                $positions[] = new Point($x - 1, $y);
            if ($this->isGarden($x + 1, $y))
                $positions[] = new Point($x + 1, $y);
            if ($this->isGarden($x, $y - 1))
                $positions[] = new Point($x, $y - 1);
            if ($this->isGarden($x, $y + 1))
                $positions[] = new Point($x, $y + 1);
        } else {
            if ($x > 0 && $this->isGarden($x - 1, $y))
                $positions[] = new Point($x - 1, $y);
            if ($x < $this->getWidth() - 1 && $this->isGarden($x + 1, $y))
                $positions[] = new Point($x + 1, $y);
            if ($y > 0 && $this->isGarden($x, $y - 1))
                $positions[] = new Point($x, $y - 1);
            if ($y < $this->getHeight() - 1 && $this->isGarden($x, $y + 1))
                $positions[] = new Point($x, $y + 1);
        }

        return $positions;
    }


    public function print(PointsList $list = null): void
    {
        $list = $list ?? new PointsList();
        for ($y = 0; $y < $this->getHeight(); ++$y)
        {
            for ($x = 0; $x < $this->getWidth(); ++$x)
            {
                if ($list->includes(new Point($x, $y)))
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

    public function explore(int $steps, Point $startingLocation, bool $debug = false): PointsList
    {
        $pointsList = new PointsList();
        $stepsMod2 = $steps % 2;

        $minY = $startingLocation->getY() - $steps;
        $maxY = $startingLocation->getY() + $steps;
        $minX = $startingLocation->getX() - $steps;
        $maxX = $startingLocation->getX() + $steps;
        $minX = max($minX, 0);
        $minY = max($minY, 0);
        $maxX = min($maxX, $this->getWidth() - 1);
        $maxY = min($maxY, $this->getHeight() - 1);
        for($y = $minY; $y <= $maxY; ++$y)
        {
            if ($debug)
            {
                print("$y / $minY - $maxY\n");
            }
            for($x = $minX; $x <= $maxX; ++$x)
            {
                if (!$this->isGarden($x, $y))
                    continue;
                $point = new Point($x, $y);
                if ($point->distance($startingLocation) > $steps)
                    continue;

                $path = $this->getPath($point, $startingLocation);
                if ($path === null)
                    continue;
                $cPath = count($path);
                if ($cPath > $steps + 1)
                    continue;
                if ($cPath % 2 === $stepsMod2)
                    continue;
                $pointsList->push($point);
            }
        }
        return $pointsList;
    }

    private array $cache = [];

    /** @return  Point[]|null */
    public function getPath(Point $start, Point $end): ?array
    {
        $cache = $this->cache[$start->hash()] ?? ['g' => [], 'f' => [], 'm' => []];
        $todo = [$start];
        $from = $cache['m'];
        $gScores = $cache['g'];
        $gScores[$start->hash()] = 0;
        $fScores = $cache['f'];
        $fScores[$start->hash()] = $start->distance($end);

        while (($current = $this->getSmallest($todo, $fScores)) !== null)
        {
            if ($current->equals($end)) {
                $this->cache[$start->hash()] = [
                    'g' => $gScores,
                    'f' => $fScores,
                    'm' => $from,
                ];
                return $this->reconstructPath($from, $current);
            }

            $cScore = $gScores[$current->hash()] ?? INF;
            foreach ($this->getNeighbors($current->getX(), $current->getY()) as $neighbor)
            {
                $hash = $neighbor->hash();
                $nScore = $gScores[$hash] ?? INF;
                $tScore = $cScore + 1;
                if ($tScore < $nScore)
                {
                    $from[$hash] = $current;
                    $gScores[$hash] = $tScore;
                    $fScores[$hash] = $tScore + $neighbor->distance($end);
                    $this->ifNotExistsAdd($todo, $neighbor);
                }
            }
        }
        return null;
    }

    /**
     * @param Point[] $list
     * @param Point $point
     * @return void
     */
    private function ifNotExistsAdd(array &$list, Point $point): void
    {
        foreach ($list as $item)
            if ($item->equals($point))
                return;
        $list[] = $point;
    }

    /**
     * @param Point[] $from
     * @return Point[]
     */
    private function reconstructPath(array $from, Point $current): array
    {
        $path = [];
        while ($current !== null)
        {
            $path[] = $current;
            $current = $from[$current->hash()] ?? null;
        }
        return $path;
    }

    /**
     * @param Point[] $list
     * @param float[] $scores
     * @return Point|null
     */
    private function getSmallest(array &$list, array $scores): ?Point
    {
        /** @var int|null $sIndex */
        $sIndex = null;
        /** @var Point|null $sPoint */
        $sPoint = null;
        /** @var float|null $sScore */
        $sScore = null;
        foreach ($list as $index => $point)
        {
            $score = $scores[$point->hash()] ?? INF;
            if ($sScore === null || $sScore > $score)
            {
                $sScore = $score;
                $sPoint = $point;
                $sIndex = $index;
            }
        }
        if ($sIndex !== null)
            array_splice($list, $sIndex, 1);
        return $sPoint;
    }

}