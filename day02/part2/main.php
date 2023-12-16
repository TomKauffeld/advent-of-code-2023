<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

$file = getInputFile();

if (!$file)
    throw new RuntimeException('file not opened');

$sum = 0;

while(($line = fgets($file)) !== false)
{
    $line = trim($line);

    if (strlen($line) < 1)
        continue;

    $s1 = explode(':', $line);
    if (count($s1) !== 2)
        print("ERROR, no split: $line\n");
    if (!preg_match('/^Game (?<number>[0-9]+)$/', $s1[0], $matches)) {
        print("ERROR, no Game Number: $line\n");
    }
    $game = intval($matches['number']);
    $sets = explode(';', $s1[1]);
    print ("Game $game: \t");

    $required = [
        'red' => 0,
        'green' => 0,
        'blue' => 0,
    ];
    foreach ($sets as $set) {
        $cubes = explode(',', $set);
        foreach ($cubes as $cube) {
            if (preg_match('/^ *(?<number>[0-9]+) (?<color>[a-z]+) *$/', $cube, $matches)) {
                $number = intval($matches['number']);
                $color = $matches['color'];
                $current = $required[$color] ?? 0;
                if ($number > $current) {
                    $required[$color] = $number;
                }
            }
        }
    }
    $power = 1;
    foreach ($required as $color => $number)
        $power *= $number;

    $sum += $power;
    print("{$required['red']} \t- {$required['green']} \t- {$required['blue']} \t- $power \t- $sum\n");
}


fclose($file);
