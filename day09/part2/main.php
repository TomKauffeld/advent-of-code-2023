<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

$file = getInputFile(9);

$sum = 0;

while (($line = fgets($file)) !== false)
{
    $numbers = getNumbers($line);
    if (count($numbers) < 1)
        continue;
    $pyramid = generatePyramid(...$numbers);
    $pyramid = addNewColumn($pyramid);
    $number = $pyramid[0][0];
    $rows = count($pyramid);
    $sum += $number;
    print("$rows\t - $number\t - $sum\n");
}


function areAllZeros(int... $numbers): bool
{
    foreach ($numbers as $number)
        if ($number !== 0)
            return false;
    return true;
}

/**
 * @param int ...$numbers
 * @return int[][]
 */
function generatePyramid(int... $numbers): array
{
    $lines = [$numbers];
    while (!areAllZeros(...($line = $lines[count($lines) - 1])))
    {
        $length = count($line);
        $next = [];
        for($i = 0; $i < $length - 1; ++$i)
            $next[$i] = $line[$i + 1] - $line[$i];
        $lines[] = $next;
    }
    return $lines;
}

/**
 * @param int[][] $pyramid
 * @return int[][]
 */
function addNewColumn(array $pyramid): array
{
    $cPyramid = count($pyramid);

    array_unshift($pyramid[$cPyramid - 1], 0);

    for ($row = $cPyramid - 2; $row >= 0; --$row)
    {
        $target = $pyramid[$row + 1][0];
        array_unshift($pyramid[$row], $pyramid[$row][0] - $target);
    }

    return $pyramid;
}



fclose($file);
