<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

$file = getInputFile(8);

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
    $cDirections = count($directions);
    while (!isset($done["$location-$step"]))
    {
        if (str_ends_with($location, 'Z'))
            $found[] = $loop * $cDirections + $step;
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
    if ($maxStart < $set['start'])
        $maxStart = $set['start'];
    $loopLengths[] = $set['length'];
}

foreach ($sets as $location => $set) {
    $offset = $maxStart - $set['start'];
    $sets[$location]['start'] += $offset;
    for($i = 0; $i < count($set['found']); ++$i) {
        $sets[$location]['found'][$i] += $offset;
    }
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

$nextFounds = array_map(static function (array $data): array {
    return $data['found'];
}, $sets);

$offset = 0;


function isEndPoint(array $founds, int $offset): bool
{
    foreach ($founds as $found)
        if (!in_array($offset, $found))
            return false;
    return true;
}

$startTime = time();
$nextTime = $startTime + 5;

while ($offset < $loopLength)
{
    if (isEndPoint($nextFounds, $offset))
    {
        print("FOUND SOLUTION AT $offset\n");
        print("OFFSET : $maxStart\n");
        $total = $offset + $maxStart;
        print("RESULT: $total\n");
        break;
    } else {
        $now = time();
        if ($nextTime < $now) {
            $timePerLoop = ($now - $startTime) / $offset;
            $timeRemaining = ($loopLength - $offset) * $timePerLoop;
            $per = round($offset * 100 / $loopLength);
            $hours = floor($timeRemaining / 3600);
            $minutes = floor(($timeRemaining - $hours * 3600) / 60);
            $seconds =  str_pad((string)round($timeRemaining - $minutes * 60 - $hours * 3600), 2, '0', STR_PAD_LEFT);
            $minutes = str_pad((string)$minutes, '2', '0', STR_PAD_LEFT);
            print ("RUNNING $per%\t $offset / $loopLength\t $hours:$minutes:$seconds\n");
            $nextTime += 5;
        }
    }

    $minFound = null;
    foreach (array_keys($sets) as $key) {
        for($i = 0; $i < count($nextFounds[$key]); ++$i)
        {
            while ($nextFounds[$key][$i] <= $offset)
                $nextFounds[$key][$i] += $sets[$key]['length'];

            if ($minFound === null || $nextFounds[$key][$i] < $minFound)
                $minFound = $nextFounds[$key][$i];
        }
    }
    $offset = $minFound;
}

