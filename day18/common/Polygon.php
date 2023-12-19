<?php

class Polygon
{
    private int $points = 0;
    private bool $finished = false;
    private array $vertices = [];
    private ?array $last = null;

    public function __construct(array ... $points)
    {
        foreach ($points as $point)
        {
            if (!isset($point['x'], $point['y']) || !is_numeric($point['x']) || !is_numeric($point['y']))
                throw new RuntimeException('invalid points');
            $this->addVertex($point['x'], $point['y']);
        }
    }

    public function addVertex(int $x, int $y): bool
    {
        if ($this->finished)
            throw new RuntimeException('Polygon already closed');
        if (isset($this->vertices[0]) && $this->vertices[0]['x'] === $x && $this->vertices[0]['y'] === $y)
            return $this->finished = true;
        if ($this->last !== null && $this->last['x'] !== $x && $this->last['y'] !== $y)
            throw new RuntimeException('Polygon not connected');

        $this->last = ['x' => $x, 'y' => $y];
        $this->vertices[] = $this->last;

        return false;
    }

    public function getPerimeter(): int
    {
        if (!$this->finished)
            throw new RuntimeException('Polygon not yet closed');
        $sum = 0;
        foreach ($this->vertices as $index => $vertex)
        {
            $next = $this->vertices[$index + 1] ?? $this->vertices[0];
            $sum += abs($vertex['x'] - $next['x']) + abs($vertex['y'] - $next['y']);
        }
        return $sum;
    }

    public function getArea(): int
    {
        if (!$this->finished)
            throw new RuntimeException('Polygon not yet closed');
        $sum = 0;
        foreach ($this->vertices as $index => $vertex)
        {
            $next = $this->vertices[$index + 1] ?? $this->vertices[0];
            $partial = $vertex['x'] * $next['y'] - $vertex['y'] * $next['x'];
            $sum += $partial / 2;
        }
        return abs($sum);
    }
}
