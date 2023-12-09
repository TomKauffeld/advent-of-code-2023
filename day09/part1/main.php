<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

$file = getInputFile(9);

$sum = 0;

while (($line = fgets($file)) !== false)
{
    $numbers = getNumbersOnLine($line);
    if (count($numbers) < 1)
        continue;
    $pyramid = generatePyramid(...$numbers);
    $pyramid = addNewColumn($pyramid);
    $number = array_last($pyramid[0]);
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


function array_last(array $arr)
{
    $cArr = count($arr);
    if ($cArr < 1)
        return null;
    return $arr[$cArr - 1];
}

/**
 * @param int[][] $pyramid
 * @return int[][]
 */
function addNewColumn(array $pyramid): array
{
    $cPyramid = count($pyramid);

    $pyramid[$cPyramid - 1][] = 0;

    for ($row = $cPyramid - 2; $row >= 0; --$row)
    {
        $target = array_last($pyramid[$row + 1]);
        $pyramid[$row][] = $target + array_last($pyramid[$row]);
    }

    return $pyramid;
}



/**
 * @param string $line
 * @return int[]
 */
function getNumbersOnLine(string $line): array
{
    $numbers = [];
    $parts = explode(' ', $line);
    foreach ($parts as $part)
    {
        $part = trim($part);
        if (strlen($part) > 0 && is_numeric($part))
            $numbers[] = intval($part);
    }
    return $numbers;
}

fclose($file);
