<?php

namespace Aoc\Y2023\Day22\Common;

use RuntimeException;

class Map
{
    /** @var Brick[] */
    private array $bricks = [];
    private array $y2i = [];

    public function addBrick(Brick $brick): void
    {
        foreach ($this->bricks as $tmp)
            if ($tmp->intersects($brick))
                throw new RuntimeException('Invalid brick');
        $index = count($this->bricks);
        $this->bricks[$index] = $brick;
        for($y = $brick->getStart()->getY(); $y <= $brick->getEnd()->getY(); ++$y)
        {
            if (!isset($this->y2i[$y]))
                $this->y2i[$y] = [];
            $this->y2i[$y][] = $index;
        }
    }

    public function addGravity(): void
    {
        $moved = true;
        while ($moved)
        {
            $moved = false;
            for ($i = 0; $i < count($this->bricks); ++$i) {
                if ($this->canBrickMoveDown($this->bricks[$i]))
                {
                    $this->moveBrickDown($i);
                    $moved = true;
                }
            }
        }
    }

    /**
     * @param Brick $brick
     * @return Brick[]
     */
    public function getBricksAbove(Brick $brick): array
    {
        $found = [];
        $indexes = $this->y2i[$brick->getEnd()->getY() + 1] ?? [];
        foreach ($indexes as $index) {
            $other = $this->bricks[$index];
            if ($brick->isOtherBrickAbove($other))
                $found[] = $other;
        }
        return $found;
    }

    /**
     * @param Brick $brick
     * @return int[]
     */
    public function getIndexAbove(Brick $brick): array
    {
        $found = [];
        $indexes = $this->y2i[$brick->getEnd()->getY() + 1] ?? [];
        foreach ($indexes as $index) {
            $other = $this->bricks[$index];
            if ($brick->isOtherBrickAbove($other))
                $found[] = $index;
        }
        return $found;
    }

    /**
     * @param Brick $brick
     * @return Brick[]
     */
    public function getBricksBelow(Brick $brick): array
    {
        $found = [];
        $indexes = $this->y2i[$brick->getStart()->getY() - 1] ?? [];
        foreach ($indexes as $index) {
            $other = $this->bricks[$index];
            if ($brick->isOtherBrickBelow($other))
                $found[] = $other;
        }
        return $found;
    }

    public function canBrickMoveDown(Brick $brick): bool
    {
        return $this->canBrickMoveDownWithout($brick);
    }

    public function getTotalFallingBricks(bool $debug = false): int
    {
        $sum = 0;
        foreach ($this->bricks as $index => $brick)
        {
            if ($debug)
                print("Checking #$index\n");
            $ignore = [$index];
            do
            {
                $indexes = $this->getFallingIndexes(...$ignore);
                $sum += count($indexes);
                array_push($ignore, ...$indexes);
            }while(count($indexes) > 0);
        }
        return $sum;

    }

    public function getFallingIndexes(int ... $without): array
    {
        $found = [];
        foreach ($this->bricks as $index => $brick)
        {
            if (in_array($index, $without))
                continue;
            if ($this->canBrickMoveDownWithoutIndex($brick, ...$without))
                $found[] = $index;
        }
        return $found;
    }

    public function canBrickMoveDownWithoutIndex(Brick $brick, int ... $without): bool
    {
        if ($brick->getStart()->getY() <= 1)
            return false;
        $indexes = $this->y2i[$brick->getStart()->getY() - 1] ?? [];
        foreach ($indexes as $index) {
            if (in_array($index, $without))
                continue;
            $other = $this->bricks[$index];
            if ($brick->isOtherBrickBelow($other))
                return false;
        }
        return true;
    }

    public function canBrickMoveDownWithout(Brick $brick, ?Brick $without = null): bool
    {
        if ($brick->getStart()->getY() <= 1)
            return false;
        $indexes = $this->y2i[$brick->getStart()->getY() - 1] ?? [];
        foreach ($indexes as $index) {
            $other = $this->bricks[$index];
            if ($without !== null && $other->equals($without))
                continue;
            if ($brick->isOtherBrickBelow($other))
                return false;
        }
        return true;
    }

    public function getIndexesOfSaveToRemove(): array
    {
        $found = [];
        foreach ($this->bricks as $index => $brick)
        {
            if ($this->canBrickBeRemoved($brick))
                $found[] = $index;
        }
        return $found;
    }

    public function canBrickBeRemoved(Brick $brick): bool
    {
        $above = $this->getBricksAbove($brick);
        foreach ($above as $item)
            if ($this->canBrickMoveDownWithout($item, $brick))
                return false;
        return true;
    }

    private function moveBrickDown(int $index): void
    {
        $start = $this->bricks[$index]->getStart();
        $end = $this->bricks[$index]->getEnd();
        $newStart = new Point($start->getX(), $start->getY() - 1, $start->getZ());
        $newEnd = new Point($end->getX(), $end->getY() - 1, $end->getZ());
        $this->bricks[$index] = new Brick($newStart, $newEnd);
        $oldY = $end->getY();
        $newY = $newStart->getY();
        if (!isset($this->y2i[$newY]))
            $this->y2i[$newY] = [];
        $this->y2i[$newY][] = $index;
        $i = array_search($index, $this->y2i[$oldY]);
        array_splice($this->y2i[$oldY], $i, 1);
    }
}