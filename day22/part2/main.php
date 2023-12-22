<?php
namespace Aoc\Y2023\Day22\Part1;

use function Aoc\Y2023\Day22\Common\parseFile;

require __DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'include.php';

$map = parseFile();

$amount = $map->getTotalFallingBricks(true);

print("Result: $amount\n");