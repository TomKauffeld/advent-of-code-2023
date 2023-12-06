<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

$file = getInputFile(6);

$times = getNumbersFromFile($file, 'Time: ');
$distances = getNumbersFromFile($file, 'Distance: ');
fclose($file);

$iMax = min(count($times), count($distances));

$result = 1;

for ($i = 0; $i < $iMax; ++$i) {
    print("Race $i:\t ");
    $possible = calculateRace($times[$i], $distances[$i]);
    $result *= $possible;
    print("$possible\t - $result\n");
}

function calculateRace(int $time, int $distance): int
{
    $possible = getPossibleWins($time, $distance);
    return count($possible);
}

function getPossibleWins(int $time, int $distance): array
{
    $possible = [];
    for($boost = 1; $boost < $time; ++$boost) {
        $d = calculateDistance($boost, $time);
        if ($d > $distance)
            $possible[] = $boost;
    }
    return $possible;
}


function calculateDistance(int $boost, int $time): int
{
    if ($boost < 1 || $boost >= $time)
        return 0;
    return ($time - $boost) * ($boost);
}



function getNumbersFromFile($file, string $prefix): array
{
    if (($line = fgets($file)) === false || !str_starts_with($line, $prefix))
        throw new RuntimeException('Invalid file format');
    return getNumbersFromLine($line, strlen($prefix));
}

function getNumbersFromLine(string $line, int $offset = 0): array
{
    $parts = explode(' ', substr($line, $offset));
    $numbers = [];
    foreach ($parts as $part) {
        $part = trim($part);
        if (strlen($part) > 0 && is_numeric($part)) {
            $numbers[] = intval($part);
        }
    }
    return $numbers;
}
