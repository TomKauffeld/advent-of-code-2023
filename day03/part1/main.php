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

function checkLine(string $numbersLine, array $symbolLines): array {
    $offset = 0;
    $numbers = [];
    while ($offset < strlen($numbersLine)) {
        if (preg_match('/(?:^|[^0-9])(?<number>[0-9]+)(?:[^0-9]|$)/', $numbersLine, $matches, PREG_OFFSET_CAPTURE, $offset)) {
            $sNumber = "{$matches['number'][0]}";
            $index = intval($matches['number'][1]);
            $number = intval($matches['number'][0]);
            $before = $index - 1;
            $jMin = max(0, $before);
            $after = $index + strlen($sNumber) + 1;
            $valid = false;
            for($i = 0; $i < count($symbolLines) && !$valid; ++$i) {
                $jMax = min(strlen($symbolLines[$i]), $after);
                for ($j = $jMin; $j < $jMax && !$valid; ++$j) {
                    if ($symbolLines[$i][$j] !== '.' && !is_numeric($symbolLines[$i][$j])) {
                        $valid = true;
                    }
                }
            }
            if ($valid) {
                $numbers[] = $number;
            }

            $offset = $index + strlen($sNumber);
        } else {
            $offset = strlen($numbersLine);
        }
    }

    return $numbers;
}
