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
    $per = 0;
    for($boost = 1; $boost < $time; ++$boost) {
        $d = calculateDistance($boost, $time);
        print("Boost $boost:\t $d\t ");
        if ($boost % 100 === 0) {
            $per =  round(($boost / $time) * 100);
        }
        if ($d > $distance) {
            $possible[] = $boost;
            print("OK\t $per%\n");
        } else {
            print("  \t $per%\n");
        }
    }
    return $possible;
}


function calculateDistance(int $boost, int $time): int
{
    if ($boost < 1 || $boost >= $time)
        return 0;
    return ($time - $boost) * ($boost);
}