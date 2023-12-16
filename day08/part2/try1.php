<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

$file = getInputFile();

const SAVE_FILE = 'save.txt';

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

function isAtFinishLine(string ... $locations): bool
{
    foreach ($locations as $location) {
        if (!str_ends_with($location, 'Z')) {
            return false;
        }
    }
    return true;
}

function saveStep(int $steps, int $loops, string ... $locations) : bool
{
    $file = fopen(SAVE_FILE, 'w');
    if ($file === false)
        return false;
    fwrite($file, "$steps\n");
    fwrite($file, "$loops\n");
    foreach ($locations as $location) {
        fwrite($file, "$location\n");
    }
    fclose($file);
    return true;
}

function returnNullAndClose($file): ?array
{
    fclose($file);
    return null;
}

function loadStep(): ?array
{
    if (!file_exists(SAVE_FILE))
        return null;
    $file = fopen(SAVE_FILE, 'r');
    if ($file === false)
        return null;
    $sStep = fgets($file);
    if ($sStep === false)
        return returnNullAndClose($file);
    $sLoop = fgets($file);
    if ($sLoop === false)
        return returnNullAndClose($file);
    $locations = [];
    while (($line = fgets($file)) !== false)
    {
        $line = trim($line);
        if (strlen($line) > 0)
            $locations[] = $line;
    }
    fclose($file);
    if (count($locations) < 1)
        return null;
    return [
        'step' => intval($sStep),
        'loop' => intval($sLoop),
        'locations' => $locations,
    ];
}

$initial = loadStep();


$locations = array_keys(array_filter($nodes, static function (string $location): bool {
    return str_ends_with($location, 'A');
}, ARRAY_FILTER_USE_KEY));

$step = 0;
$loop = 0;

if ($initial !== null) {
    $locations = $initial['locations'];
    $step = $initial['step'];
    $loop = $initial['loop'];
}

$cDirections = count($directions);
$cLocations = count($locations);
while ($step >= $cDirections)
{
    ++$loop;
    $step -= $cDirections;
}

$lTime = time();

while (!isAtFinishLine(...$locations) && $loop < 100_000_000)
{
    $direction = $directions[$step++];

    while ($step >= $cDirections)
    {
        ++$loop;
        $step -= $cDirections;
    }

    for($i = 0; $i < $cLocations; ++$i) {
        if (!isset($nodes[$locations[$i]]) || !isset($nodes[$locations[$i]][$direction]))
            throw new RuntimeException("Cannot find direction $locations[$i] -> $direction");
        $locations[$i] = $nodes[$locations[$i]][$direction];
    }

    if (time() - $lTime > 5) {
        $lTime += 5;
        saveStep($step, $loop, ...$locations);
        $total = $loop * $cDirections + $step;
        print("$loop\t - $step\t - $cDirections\t - $total\n");
    }
}

if (isAtFinishLine(...$locations)) {
    print("DONE: \n");
} else {
    print("OUT OF LOOPS: \n");
}
$total = $loop * $cDirections + $step;
print("\tLOOPS:      $loop\n");
print("\tSTEPS:      $step\n");
print("\tDIRECTIONS: $cDirections\n");
print("\tTOTAL:      $total\n");
