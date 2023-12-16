<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

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

function getLoopLength(array $nodes, array $directions, string $location): int {
    $step = 0;
    $loop = 0;
    $done = [];
    $cDirections = count($directions);
    while (!isset($done["$location-$step"]))
    {
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
    return  $loop * $cDirections + $step - $done["$location-$step"];
}

$locations = array_keys(array_filter($nodes, static function (string $location): bool {
    return str_ends_with($location, 'A');
}, ARRAY_FILTER_USE_KEY));

$loopLengths = [];
foreach ($locations as $location) {
    $loopLengths[] = getLoopLength($nodes, $directions, $location);
}

function gcd(int $a, int $b): int
{
    if ($a === $b || $b === 0 || $a === 0)
        return max($a, $b);
    if ($a < $b)
        return gcd($a, $b % $a);
    return gcd($b, $a % $b);
}

function lcm(int $a, int $b): int
{
    $gcd = gcd($a, $b);
    if ($gcd === 0)
        return 0;
    return $a * ($b / $gcd);
}

while (count($loopLengths) > 1) {
    $a = array_shift($loopLengths);
    $b = array_shift($loopLengths);
    $loopLengths[] = lcm($a, $b);
}
$loopLength = $loopLengths[0];

print ("Loop length: $loopLength\n");
