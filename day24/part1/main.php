<?php
namespace Aoc\Y2023\Day24\Part1;

use function Aoc\Y2023\Day24\Common\parseFile;

require __DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'include.php';

const MIN_VALUE = 200000000000000;
const MAX_VALUE = 400000000000000;

function isBetween(float $value, float $min, float $max): bool
{
    return $value >= $min && $value <= $max;
}

$map = parseFile();

$intersections = $map->getIntersections();
$ci = count($intersections);
$total = 0;
foreach ($intersections as $intersection) {
    if (isBetween($intersection->getX(), MIN_VALUE, MAX_VALUE) && isBetween($intersection->getY(), MIN_VALUE, MAX_VALUE))
        ++$total;
}
print("Intersections: $ci\n");
print("Inside:        $total\n");