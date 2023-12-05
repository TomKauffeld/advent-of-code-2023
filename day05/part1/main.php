<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

$file = getInputFile(5);

if (!$file)
    throw new RuntimeException('file not opened');

$numbers = getSeeds(fgets($file));
$_ = fgets($file);

while (!feof($file)) {
    $numbers = parse($file, $numbers);
}


fclose($file);





function getSeeds(string $line): array {
    $prefix = 'seeds: ';
    if (!str_starts_with($line, $prefix)) {
        return [];
    }
    $subLine = substr($line, strlen($prefix));

    $numbers = [];
    $parts = explode(' ', $subLine);
    foreach ($parts as $part) {
        $trimmed = trim($part);
        if (strlen($trimmed) > 0 && is_numeric($trimmed)) {
            $numbers[] = intval($trimmed);
        }
    }
    return $numbers;
}

function parse($file, array $previousNumbers): array {
    $name = rtrim(trim(fgets($file)), ':');
    print("$name:\t ");
    $map = getMap($file);
    $nextNumbers = array_map(static function (int $value) use ($map): int {
        foreach ($map as $mapValue) {
            if ($mapValue['start'] <= $value && $mapValue['end'] >= $value) {
                return $value + $mapValue['offset'];
            }
        }
        return $value;
    }, $previousNumbers);

    $str = join("\t ", $nextNumbers);
    $m = min(...$nextNumbers);
    print ("$str\t - $m\n");

    return $nextNumbers;
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
