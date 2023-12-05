<?php

$file = fopen(join(DIRECTORY_SEPARATOR, ['..', 'data', 'input.txt']), 'r');

if (!$file)
    throw new RuntimeException('file not opened');


$lineMin2 = null;
$lineMin1 = null;
$sum = 0;

while (($line = fgets($file)) !== false || $lineMin1 !== null) {
    $line = $line === false ? null : trim($line);

    if ($lineMin1 !== null && strlen($lineMin1) > 0) {
        $check = [$lineMin1];

        if ($line !== null && strlen($line) > 0)
            $check[] = $line;
        if ($lineMin2 !== null && strlen($lineMin2) > 0)
            $check[] = $lineMin2;

        $numbers = checkLine($lineMin1, $check);
        $amount = count($numbers);

        $localSum = array_reduce($numbers, static function (int $a, int $i) {
            return $a + $i;
        }, 0);

        $sum += $localSum;

        print("$amount\t - $localSum\t - $sum\n");
    }

    $lineMin2 = $lineMin1;
    $lineMin1 = $line;
}

fclose($file);
function checkLine(string $line, array $around): array {
    $offset = 0;
    $results = [];
    while ($offset < strlen($line)) {
        $index = strpos($line, '*', $offset);
        if ($index !== false) {
            $numbers = [];
            foreach ($around as $l) {
                $numbers = array_merge($numbers, getNumbersOfLine($l, $index));
            }

            if (count($numbers) === 2) {
                $mul = $numbers[0] * $numbers[1];
                $results[] = $mul;
            }

            $offset = $index + 1;
        } else {
            $offset = strlen($line);
        }
    }

    return $results;
}

function getNumbersOfLine(string $line, int $offset): array {
    if (is_numeric($line[$offset])) {
        $number = getNumberAtValue($line, $offset);
        return $number !== null ? [$number] : [];
    } else {
        $left = getNumberAtValue($line, $offset - 1);
        $right = getNumberAtValue($line, $offset + 1);
        $numbers = [];
        if ($left !== null)
            $numbers[] = $left;
        if ($right !== null)
            $numbers[] = $right;
        return $numbers;
    }
}

function getNumberAtValue(string $line, int $offset): ?int {
    if ($offset < 0 || $offset >= strlen($line) || !is_numeric($line[$offset])) {
        return null;
    }
    $left = $offset;
    $right = $offset;
    while ($left >= 0 && is_numeric($line[$left]))
        --$left;
    while ($right < strlen($line) && is_numeric($line[$right]))
        ++$right;
    ++$left;
    --$right;

    $text = substr($line, $left, $right - $left + 1);

    return intval($text);
}
