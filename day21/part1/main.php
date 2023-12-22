<?php
namespace Aoc\Y2023\Day21\Part1;

use function Aoc\Y2023\Day21\Common\parseFile;

require __DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'include.php';

const TEST = TRUE;

const STEPS = TEST ?  3 * 5 - 2 : 64;

$file = parseFile(TEST);

$positions = $file->map->explore(STEPS, $file->start, true);

$file->map->print($positions);
$count = $positions->count();

print("Positions: $count\n");