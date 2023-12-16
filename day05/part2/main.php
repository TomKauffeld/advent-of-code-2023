<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

require_once __DIR__ . DIRECTORY_SEPARATOR . 'NumberRange.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'NumberMap.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'NumberMaps.php';

$file = getInputFile();


$seeds = [];
$steps = [];
$currentMap = null;

function parseSeeds(string $line): array
{
    $line = trim(substr($line, strlen('seeds:')));
    $numbers = getNumbers($line);
    /** @var int|null $start */
    $start = null;
    /** @var NumberRange[] $seeds */
    $seeds = [];
    foreach ($numbers as $iNumber) {
        if ($start === null) {
            $start = $iNumber;
        } else {
            $seeds[] = new NumberRange($start, $iNumber);
            $start = null;
        }
    }
    if ($start !== null)
        throw new RuntimeException('invalid number of seeds');
    return $seeds;
}



while (($line = fgets($file)) !== false) {
    $line = trim($line);
    if (str_starts_with($line, 'seeds: ')) {
        $seeds = parseSeeds($line);
    } elseif (strlen($line) < 1) {
        if ($currentMap !== null) {
            $steps[] = new NumberMaps(...$currentMap);
        }
        $currentMap = null;
    } elseif (preg_match('/^[a-z-]+ map:$/', $line)) {
        if ($currentMap !== null)
            throw new RuntimeException('invalid format');
        $currentMap = [];
    } elseif (preg_match('/^(?<drs>[0-9]+) (?<srs>[0-9]+) (?<rl>[0-9]+)$/', $line, $matches)) {
        if ($currentMap === null)
            throw new RuntimeException('invalid format');
        $drs = intval($matches['drs']);
        $srs = intval($matches['srs']);
        $rl = intval($matches['rl']);
        $currentMap[] = new NumberMap($srs, $rl, $drs - $srs);
    } else {
        throw new RuntimeException('invalid format');
    }
}

if ($currentMap !== null) {
    $steps[] = new NumberMaps(...$currentMap);
}

fclose($file);


print('Seeds: ' . count($seeds) . "\n");
print('Steps: ' . count($steps) . "\n");

$current = $seeds;
foreach ($steps as $step) {
    $current = $step->offsetRanges(...$current);
}

$smallest = getSmallestRange(...$current);
print($smallest->getStart() . "\n");


function getSmallestRange(NumberRange ... $ranges): ?NumberRange
{
    $smallest = null;
    foreach ($ranges as $range)
        if ($smallest === null || $smallest->getStart() > $range->getStart())
            $smallest = $range;
    return $smallest;
}
