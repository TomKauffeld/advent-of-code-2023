<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

$file = getInputFile();

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

function getLoopSteps(array $nodes, array $directions, string $location): array {
    $step = 0;
    $loop = 0;
    $done = [];
    $found = [];
    $cycle = [];
    $cDirections = count($directions);
    while (!isset($done["$location-$step"]))
    {
        if (str_ends_with($location, 'Z'))
            $found[] = $loop * $cDirections + $step;
        $cycle[] = $location;
        $done["$location-$step"] = $loop * $cDirections + $step;
        $direction = $directions[$step++];
        if ($step >= $cDirections) {
            $step -= $cDirections;
            ++$loop;
        }
        if (!isset($nodes[$location]) || !isset($nodes[$location][$direction]))
            throw new RuntimeException("Cannot find direction $location -> $direction");
        $location = $nodes[$location][$direction];
    }
    $foundOffsets = [];
    foreach ($found as $item)
        if ($item >= $done["$location-$step"])
            $foundOffsets[] = $item - $done["$location-$step"];
    return [
        'start' => $done["$location-$step"],
        'length' => $loop * $cDirections + $step - $done["$location-$step"],
        'found' => $foundOffsets,
        'cycle' => $cycle,
    ];
}

$locations = array_keys(array_filter($nodes, static function (string $location): bool {
    return str_ends_with($location, 'A');
}, ARRAY_FILTER_USE_KEY));

$sets = [];
foreach ($locations as $location) {
    $sets[$location] = getLoopSteps($nodes, $directions, $location);
}

$maxStart = 0;

$loopLengths = [];
foreach ($sets as $location => $set) {
    $last = $set['cycle'][count($set['cycle']) - $set['start']];
    print ("$location -> $last\n");
}
