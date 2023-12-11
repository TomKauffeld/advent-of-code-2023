<?php


/**
 * @param int|string $day
 * @param int $offsetSize
 * @return array{x: int, y: int}[]
 */
function readInputFile($day, int $offsetSize = 1): array
{
    if (is_numeric($day))
        $file = getInputFile($day);
    elseif (is_string($day))
        $file = fopen($day, 'r');
    else
        throw new InvalidArgumentException('day should be a day number or a file path');

    $positions = [];

    $y = 0;
    $doOffsets = [];
    $yOffset = 0;
    while(($line = fgets($file)) !== false)
    {
        $line = trim($line);
        $sLine = strlen($line);
        $found = 0;
        for ($x = 0; $x < $sLine; ++$x)
        {
            if ($line[$x] === '#') {
                $doOffsets[$x] = false;
                $positions[] = [
                    'x' => $x,
                    'y' => $y + $yOffset,
                ];
                ++$found;
            } elseif (!isset($doOffsets[$x])) {
                $doOffsets[$x] = true;
            }
        }
        if ($found < 1)
            $yOffset += $offsetSize;
        ++$y;
    }

    fclose($file);

    $xOffsets = [];
    $xOffset = 0;
    foreach ($doOffsets as $x => $doOffset)
    {
        if ($doOffset) {
            $xOffset += $offsetSize;
        }
        $xOffsets[$x] = $xOffset;
    }

    foreach ($positions as $index => $position) {
        $positions[$index]['x'] += $xOffsets[$position['x']];
    }

    return $positions;
}

/**
 * @param array{x: int, y: int} $a
 * @param array{x: int, y: int} $b
 * @return int
 */
function distance(array $a, array $b): int
{
    $dx = $b['x'] - $a['x'];
    $dy = $b['y'] - $a['y'];
    return abs($dx) + abs($dy);
}


/**
 * @param array{x: int, y: int} $positions
 * @return int[][]
 */
function getDistanceMap(array $positions): array
{
    $distanceMap = [];

    $cPositions = count($positions);
    for ($i = 0; $i < $cPositions; ++$i)
    {
        if (!isset($distanceMap[$i]))
            $distanceMap[$i] = [];
        for ($j = $i; $j < $cPositions; ++$j)
        {
            $d = distance($positions[$i], $positions[$j]);
            $distanceMap[$i][$j] = $d;

            if (!isset($distanceMap[$j]))
                $distanceMap[$j] = [];

            $distanceMap[$j][$i] = $d;
        }
    }

    return $distanceMap;
}
