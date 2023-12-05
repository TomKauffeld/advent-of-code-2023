<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'NumberRange.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'NumberRangeQueue.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'NumberMap.php';

class NumberMaps
{
    /** @var NumberMap[] */
    private array $maps;

    public function __construct(NumberMap ... $maps)
    {
        $this->maps = $maps;
    }

    /***
     * @param NumberRange $range
     * @return NumberMap[]
     */
    public function getApplicableMaps(NumberRange $range): array
    {
        $maps = [];
        foreach ($this->maps as $map) {
            if ($map->intersects($range))
                $maps[] = $map;
        }
        return $maps;
    }

    public function getSmallestApplicableMap(NumberRange $range): ?NumberMap
    {
        /** @var NumberMap|null $smallest */
        $smallest = null;
        foreach ($this->maps as $map) {
            if ($map->intersects($range) && ($smallest === null || $smallest->getStart() > $map->getStart())) {
                $smallest = $map;
            }
        }
        return $smallest;
    }

    public function offsetRanges(NumberRange ... $ranges): array
    {
        $done = [];
        $todo = new NumberRangeQueue(...$ranges);

        while (($range = $todo->shift()) !== null) {
            print('TODO: ' . $todo->count() . "\n");
            $map = $this->getSmallestApplicableMap($range);
            if ($map !== null) {
                $result = $map->offsetRange($range);
                $done[] = $result->getDone();
                $todo->add(...$result->getIgnored());
            }
        }

        return $done;
    }
}
