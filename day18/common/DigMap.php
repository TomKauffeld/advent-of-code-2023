<?php

class DigMap
{
    private ?int $minX = null;
    private ?int $minY = null;
    private ?int $maxX = null;
    private ?int $maxY = null;
    private array $data = [];

    public function set(int $x, int $y, $value)
    {
        if ($this->minX === null || $this->minX > $x)
            $this->minX = $x;
        if ($this->maxX === null || $this->maxX < $x)
            $this->maxX = $x;
        if ($this->minY === null || $this->minY > $y)
            $this->minY = $y;
        if ($this->maxY === null || $this->maxY < $y)
            $this->maxY = $y;

        if (!isset($this->data[$y]))
            $this->data[$y] = [];
        $this->data[$y][$x] = $value;
    }

    protected function validateReady(): void
    {
        if (!$this->hasData())
            throw new RuntimeException('Map not yet ready');
    }

    protected function validatePosition(int $x, int $y): void
    {
        if ($x < $this->getMinX() || $x > $this->getMaxX())
            throw new RuntimeException('invalid x');
        if ($y < $this->getMinY() || $y > $this->getMaxY())
            throw new RuntimeException('invalid y');
    }

    public function hasData(): bool
    {
        return $this->minY !== null && $this->maxY !== null && $this->minX !== null && $this->maxX !== null;
    }

    public function getWidth(): int
    {
        $this->validateReady();
        return $this->getMaxX() - $this->getMinX() + 1;
    }

    public function getHeight(): int
    {
        $this->validateReady();
        return $this->getMaxY() - $this->getMinY() + 1;
    }

    public function getValue(int $x, int $y)
    {
        $this->validatePosition($x, $y);
        if (!isset($this->data[$y]))
            return null;
        return $this->data[$y][$x] ?? null;
    }

    public function getMinX(): int
    {
        $this->validateReady();
        return $this->minX;
    }

    public function getMinY(): int
    {
        $this->validateReady();
        return $this->minY;
    }

    public function getMaxX(): int
    {
        $this->validateReady();
        return $this->maxX;
    }

    public function getMaxY(): int
    {
        $this->validateReady();
        return $this->maxY;
    }

    public function getDataSize(): int
    {
        return array_reduce($this->data, static function (int $acc, array $value): int {
            return $acc + count($value);
        }, 0);
    }

    public function display(callable $renderer = null)
    {
        for ($y = $this->getMinY(); $y <= $this->getMaxY(); ++$y)
        {
            for ($x = $this->getMinX(); $x <= $this->getMaxX(); ++$x)
            {
                $value = $this->getValue($x, $y);
                if ($renderer !== null)
                    $char = $renderer($value);
                elseif ($value === null)
                    $char = '.';
                else
                    $char = '#';
                print($char);
            }
            print("\n");
        }
    }
}
