<?php

$file = fopen(join(DIRECTORY_SEPARATOR, ['..', 'data', 'input.txt']), 'r');

if (!$file)
    throw new RuntimeException('file not opened');

$sum = 0;

while(($line = fgets($file)) !== false)
{
    if (strlen($line) < 2)
        continue;


    if (preg_match('/^[^0-9]*(?<first>[0-9]).*(?<last>[0-9])[^0-9]*$/', $line, $matches)) {
        $first = intval($matches['first']);
        $last = intval($matches['last']);
        $number = $first * 10 + $last;
        $sum += $number;
        print("$number - $sum");
        print("\n");
    } elseif (preg_match('/^[^0-9]*(?<first>[0-9])[^0-9]*$/', $line, $matches)) {
        $first = intval($matches['first']);
        $number = $first * 10 + $first;
        $sum += $number;
        print("$number - $sum");
        print("\n");
    } else {
        print('NO NUMBER FOUND');
    }
}


fclose($file);
