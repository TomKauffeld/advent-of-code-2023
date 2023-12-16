<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

const NORTH = 0;
const WEST = 1;
const SOUTH = 2;
const EAST = 3;

$map = getMap();
$h = count($map);
$w = count($map[0]);
$sum = 0;

for ($y = 0; $y < $h; ++$y)
{
    $east = getEnergized($map, 0, $y, EAST);
    if ($east > $sum)
        $sum = $east;
    $west = getEnergized($map, $w - 1, $y, WEST);
    if ($west > $sum)
        $sum = $east;
}

for ($x = 0; $x < $w; ++$x)
{
    $north = getEnergized($map, $x, $h - 1, NORTH);
    if ($north > $sum)
        $sum = $north;
    $south = getEnergized($map, $x, 0, SOUTH);
    if ($south > $sum)
        $sum = $south;
}


print ("Energized: $sum\n");


function getEnergized(array $map, int $x, int $y, int $direction): int
{
    energize($map, $x, $y, $direction);
    $sum = 0;
    foreach ($map as $row)
        foreach ($row as $col)
            if ($col['charged'][NORTH] || $col['charged'][WEST] || $col['charged'][SOUTH] || $col['charged'][EAST])
                ++$sum;
    return $sum;
}



function getMap(bool $test = false): array
{
    $file = getInputFile($test);

    $map = [];
    while (($line = fgets($file)) !== false)
    {
        $line = trim($line);
        $sLine = strlen($line);
        if ($sLine < 1)
            continue;
        $map[] = array_map(static function (string $char): array {
            return [
                'char' => $char,
                'charged' => [
                    NORTH => false,
                    WEST => false,
                    SOUTH => false,
                    EAST => false,
                ]
            ];
        }, str_split($line));
    }

    fclose($file);
    return $map;
}

function energize(array &$map, int $x, int $y, int $direction): void
{
    while (isset($map[$y][$x]) && !$map[$y][$x]['charged'][$direction])
    {
        $map[$y][$x]['charged'][$direction] = true;
        $char = $map[$y][$x]['char'];
        if ($char === '-' && ($direction === NORTH || $direction === SOUTH)) {
            energize($map, $x - 1, $y, WEST);
            $direction = EAST;
        } elseif ($char === '|' && ($direction === EAST || $direction === WEST)) {
            energize($map, $x, $y - 1, NORTH);
            $direction = SOUTH;
        } elseif (($char === '\\' && $direction === EAST) || ($char === '/' && $direction === WEST)) {
            $direction = SOUTH;
        } elseif (($char === '\\' && $direction === WEST) || ($char === '/' && $direction === EAST)) {
            $direction = NORTH;
        } elseif (($char === '\\' && $direction === NORTH) || ($char === '/' && $direction === SOUTH)) {
            $direction = WEST;
        } elseif (($char === '\\' && $direction === SOUTH) || ($char === '/' && $direction === NORTH)) {
            $direction = EAST;
        }

        switch ($direction)
        {
            case NORTH:
                --$y;
                break;
            case SOUTH:
                ++$y;
                break;
            case WEST:
                --$x;
                break;
            case EAST:
                ++$x;
                break;
        }
    }
}
