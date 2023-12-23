<?php

namespace Aoc\Y2023\Day23\Common;

use InvalidArgumentException;
use RuntimeException;

class Map
{
    public const PATH = 0;
    public const WALL = 1;
    public const SLOPE = 2;
    public const SLOPE_L = 4 | self::SLOPE;
    public const SLOPE_R = 8 | self::SLOPE;
    public const SLOPE_U = 16 | self::SLOPE;
    public const SLOPE_D = 34 | self::SLOPE;


    private array $data;
    private int $width;
    private int $height;

    public function __construct(array $data, int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;
        $this->data = $data;
        if (count($data) !== $width * $height)
            throw new RuntimeException('Invalid data');
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getIndex(Point $point): int
    {
        $this->validateData($point->getX(), $point->getY());
        return $point->getX() + $point->getY() * $this->getWidth();
    }

    protected function validateData(int $x, int $y): void
    {
        if ($x < 0 || $x >= $this->getWidth() || $y < 0 || $y >= $this->getHeight())
            throw new InvalidArgumentException();
    }

    public function getDataAt(Point $point): int
    {
        $index = $this->getIndex($point);
        return $this->data[$index];
    }

    public function isPath(Point $point): bool
    {
        return $this->getDataAt($point) === self::PATH;
    }

    public function isWall(Point $point): bool
    {
        return $this->getDataAt($point) === self::WALL;
    }

    public function isSlope(Point $point): bool
    {
        return ($this->getDataAt($point) & self::SLOPE) === self::SLOPE;
    }

    public function canGoTo(Point $point, int $dx, int $dy, bool $respectSlopes = true): bool
    {
        if (abs($dy + $dx) !== 1 || $dx > 1 || $dx < -1)
            throw new RuntimeException('Invalid delta');

        if ($point->getX() + $dx < 0 || $point->getX() + $dx >= $this->getWidth())
            return false;
        if ($point->getY() + $dy < 0 || $point->getY() + $dy >= $this->getHeight())
            return false;

        if ($respectSlopes && $this->isSlope($point))
        {
            switch ($this->getDataAt($point))
            {
                case self::SLOPE_D:
                    if ($dy !== 1)
                        return false;
                    break;
                case self::SLOPE_U:
                    if ($dy !== -1)
                        return false;
                    break;
                case self::SLOPE_L:
                    if ($dx !== -1)
                        return false;
                    break;
                case self::SLOPE_R:
                    if ($dx !== 1)
                        return false;
                    break;
            }
        }
        $p2 = new Point($point->getX() + $dx, $point->getY() + $dy);
        $data = $this->getDataAt($p2);
        switch ($data)
        {
            case self::PATH:
                return true;
            case self::SLOPE_R:
                return $dx !== -1 || !$respectSlopes;
            case self::SLOPE_L:
                return $dx !== 1 || !$respectSlopes;
            case self::SLOPE_U:
                return $dy !== 1 || !$respectSlopes;
            case self::SLOPE_D:
                return $dy !== -1 || !$respectSlopes;
            default:
                return false;
        }
    }

    /**
     * @param Point $point
     * @param bool $respectSlopes
     * @return Point[]
     */
    public function getNeighbors(Point $point, bool $respectSlopes = true): array
    {
        $neighbors = [];

        if ($this->canGoTo($point, -1, 0, $respectSlopes))
            $neighbors[] = $point->left();
        if ($this->canGoTo($point, 1, 0, $respectSlopes))
            $neighbors[] = $point->right();
        if ($this->canGoTo($point, 0, -1, $respectSlopes))
            $neighbors[] = $point->up();
        if ($this->canGoTo($point, 0, 1, $respectSlopes))
            $neighbors[] = $point->down();

        return $neighbors;
    }

    public function toGraph(bool $ignoreSlopes = false): Graph
    {
        $graph = new Graph();
        $intersections = [];
        for ($y = 0; $y < $this->getHeight(); ++$y)
        {
            for ($x = 0; $x < $this->getWidth(); ++$x)
            {
                $point = new Point($x, $y);
                if ($this->isWall($point))
                    continue;
                if ($this->isIntersection($point)) {
                    $intersections[] = $graph->addNode($point);
                }
            }
        }
        foreach ($intersections as $intersection)
        {
            $point = $graph->getNode($intersection);
            $possible = [
                $this->getNextIntersectionFrom($point, -1, 0),
                $this->getNextIntersectionFrom($point, 1, 0),
                $this->getNextIntersectionFrom($point, 0, -1),
                $this->getNextIntersectionFrom($point, 0, 1),
            ];
            foreach ($possible as $item)
            {
                if ($item === null)
                    continue;
                /** @var Point $point */
                /** @var int $length */
                $point = $item['point'];
                $length = $item['length'];
                $graph->addEdge($intersection, $point->hash(), $length);
                if ($ignoreSlopes)
                    $graph->addEdge($point->hash(), $intersection, $length);
            }
        }
        return $graph;
    }

    public function isIntersection(Point $point): bool
    {
        $neighbors = $this->getNeighbors($point, false);
        return count($neighbors) !== 2;
    }

    private function getNextIntersectionFrom(Point $point, int $dx, int $dy): ?array
    {
        if (abs($dx + $dy) !== 1 || $dx > 1 || $dx < -1 || $dy > 1 || $dy < -1)
            throw new RuntimeException();
        if (!$this->canGoTo($point, $dx, $dy))
            return null;

        $nextPoint = new Point($point->getX() + $dx, $point->getY() + $dy);

        $done = [$point->hash()];
        $length = 1;
        while ($nextPoint !== null && !$this->isIntersection($nextPoint))
        {
            ++$length;
            $done[] = $nextPoint->hash();
            $neighbors = $this->getNeighbors($nextPoint);
            $nextPoint = null;
            foreach ($neighbors as $neighbor)
                if (!in_array($neighbor->hash(), $done))
                    $nextPoint = $neighbor;
        }
        if ($nextPoint === null)
            return null;
        return ['point' => $nextPoint, 'length' => $length];
    }
}