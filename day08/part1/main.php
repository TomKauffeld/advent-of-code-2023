<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

$file = getInputFile(8);

const START = 'AAA';
const TARGET = 'ZZZ';

$directions = str_split(trim(fgets($file)));

$nodes = [];

while (($line = fgets($file)) !== false)
{
    $line = trim($line);
    if (preg_match('/^(?<source>[A-Z]+) = \((?<left>[A-Z]+), (?<right>[A-Z]+)\)$/', $line, $matches))
    {
        $nodes[$matches['source']] = ['L' => $matches['left'], 'R' => $matches['right']];
    }
}

fclose($file);


$location = START;
$steps = 0;

while ($location !== TARGET && $steps < 1_000_000)
{
    $direction = $directions[$steps++ % count($directions)];
    if (!isset($nodes[$location]) || !isset($nodes[$location][$direction]))
        throw new RuntimeException("Cannot find direction $location -> $direction");
    $next = $nodes[$location][$direction];
    print("$location ($direction) -> $next\t - $steps\n");
    $location = $next;
}
