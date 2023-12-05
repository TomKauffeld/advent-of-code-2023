<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'NumberRange.php';

class NumberRangeQueue
{
    private array $items;

    public function __construct(NumberRange... $ranges)
    {
        $this->items = $ranges;
    }

    public function shift(): ?NumberRange
    {
        return array_shift($this->items);
    }

    public function add(NumberRange ... $ranges): void
    {
        $this->items = array_merge($this->items, $ranges);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

}
