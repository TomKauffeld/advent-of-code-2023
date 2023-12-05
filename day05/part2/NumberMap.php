<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'NumberRange.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'NumberMapResult.php';

class NumberMap extends NumberRange
{
    private int $offset;

    public function __construct(int $start, int $length, int $offset)
    {
        parent::__construct($start, $length);
        $this->offset = $offset;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function offsetNumber(int $number): int
    {
        if (!$this->includes($number))
            throw new RuntimeException('number not included in range');
        return $number + $this->getOffset();
    }

    public function offsetRange(NumberRange $range): NumberMapResult
    {
        if (!$this->intersects($range))
            throw new RuntimeException('range not included in map');
        $intersection = $this->getIntersection($range);
        $done = new NumberRange($this->offsetNumber($intersection->getStart()), $intersection->getLength());
        $ignored = [];

        if ($intersection->getStart() > $range->getStart())
            $ignored[] = new NumberRange($range->getStart(), $intersection->getStart() - $range->getStart());
        if ($intersection->getEnd() < $range->getEnd())
            $ignored[] = new NumberRange($intersection->getEnd(), $range->getEnd() - $intersection->getEnd());

        return new NumberMapResult($done, ...$ignored);
    }

}
