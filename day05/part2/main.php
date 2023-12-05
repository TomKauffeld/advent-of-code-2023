<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

$file = getInputFile(5);

if (!$file)
    throw new RuntimeException('file not opened');

$ranges = getSeeds(fgets($file));
$_ = fgets($file);

$maps = [];
while (!feof($file)) {
    $maps[] = parse($file);
}

fclose($file);

foreach ($ranges as $range) {
    $numbers = [];
    $start = $range['start'];
    $end = $range['end'];
    $length = $end - $start;
    print("$start -> $end ($length)\t : ");
    for($i = $range['start']; $i < $range['end']; ++$i)
        $numbers[] = $i;
    $result = traitNumbers($maps, $numbers);
    $minValue = min(...$result);
    print("$minValue\n");
}



function traitNumbers(array $maps, array $numbers): array {
    foreach ($maps as $map) {
        $numbers = traitNumbersOnMap($map, $numbers);
    }
    return $numbers;
}

function traitNumbersOnMap(array $map, array $previousNumbers): array {
    return array_map(static function (int $value) use ($map): int {
        foreach ($map as $mapValue) {
            if ($mapValue['start'] <= $value && $mapValue['end'] >= $value) {
                return $value + $mapValue['offset'];
            }
        }
        return $value;
    }, $previousNumbers);
}



function getSeeds(string $line): array {
    $prefix = 'seeds: ';
    if (!str_starts_with($line, $prefix)) {
        return [];
    }
    $subLine = substr($line, strlen($prefix));

    $ranges = [];
    $start = null;
    $parts = explode(' ', $subLine);
    foreach ($parts as $part) {
        $trimmed = trim($part);
        if (strlen($trimmed) > 0 && is_numeric($trimmed)) {
            $number = intval($trimmed);
            if ($start === null) {
                $start = $number;
            } else {
                $end = $start + $number;
                $ranges[] = [
                    'start' => $start,
                    'end' => $end,
                ];
                print("$start -> $end: $number\n");
                $start = null;
            }
        }
    }
    if ($start !== null) {
        throw new RuntimeException('invalid number of entries (should be by pair)');
    }
    return $ranges;
}

function parse($file): array {
    $name = rtrim(trim(fgets($file)), ':');
    print("$name\n");
    return getMap($file);
}


function getMap($file): array {
    $map = [];
    while (($line = fgets($file)) !== false && strlen(trim($line)) > 0) {
        if (preg_match('/^(?<drs>[0-9]+) (?<srs>[0-9]+) (?<rl>[0-9]+)$/', trim($line), $matches)) {
            $drs = intval($matches['drs']);
            $srs = intval($matches['srs']);
            $rl = intval($matches['rl']);
            $map[] = [
                'start' => $srs,
                'end' => $srs + $rl,
                'offset' => $drs - $srs,
            ];
        }
    }
    return $map;
}
