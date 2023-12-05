<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

$file = getInputFile(1);

if (!$file)
    throw new RuntimeException('file not opened');

$sum = 0;

$detect = [
    'one' => 1,
    'two' => 2,
    'three' => 3,
    'four' => 4,
    'five' => 5,
    'six' => 6,
    'seven' => 7,
    'eight' => 8,
    'nine' => 9,
    '1' => 1,
    '2' => 2,
    '3' => 3,
    '4' => 4,
    '5' => 5,
    '6' => 6,
    '7' => 7,
    '8' => 8,
    '9' => 9,
];

while(($line = fgets($file)) !== false)
{
    if (strlen($line) < 2)
        continue;
    $parts = [];
    $index = 0;
    while ($index < strlen($line)) {
        $smallest_f = null;
        $smallest_i = null;

        foreach ($detect as $key => $value) {
            $key = "$key";
            $i = strpos($line, $key, $index);
            if ($i !== false && ($smallest_i === null || $smallest_i > $i)) {
                $smallest_i = $i;
                $smallest_f = $key;
            }
        }
        if ($smallest_i !== null) {
            $parts[] = $detect[$smallest_f];
            $index = $smallest_i + 1;
        } else {
            $index = strlen($line);
        }
    }
    if (count($parts) < 1) {
        print("ERROR\n");
    } else {
        $first = intval($parts[0]);
        $last = intval($parts[count($parts) - 1]);
        $number = $first * 10 + $last;
        $sum += $number;
        print("$number - $sum\n");
    }
}


fclose($file);
