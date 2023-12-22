<?php
namespace Aoc\Y2023\Day22\Part1;

use function Aoc\Y2023\Day22\Common\parseFile;

require __DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'include.php';

$map = parseFile();

$saveToRemove = $map->getIndexesOfSaveToRemove();

$amount = count($saveToRemove);

print("Can remove $amount bricks\n");