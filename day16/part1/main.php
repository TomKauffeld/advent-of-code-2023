<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

const NORTH = 0;
const WEST = 1;
const SOUTH = 2;
const EAST = 3;

$map = getMap(16);


energize($map, 0, 0, EAST);

$sum = 0;

foreach ($map as $row)
{
    foreach ($row as $col)
    {
        if ($col['charged'][NORTH] || $col['charged'][WEST] || $col['charged'][SOUTH] || $col['charged'][EAST])
            ++$sum;

        if (in_array($col['char'], ['|', '-', '\\', '/']))
            print($col['char']);
        elseif ($col['charged'][NORTH])
            print('^');
        elseif ($col['charged'][WEST])
            print('<');
        elseif ($col['charged'][SOUTH])
            print('v');
        elseif ($col['charged'][EAST])
            print('>');
        else
            print('.');
    }
    print("\n");
}

print ("Energized: $sum\n");




function getMap($day): array
{
    if (is_numeric($day))
        $file = getInputFile(intval($day));
    elseif (is_string($day))
        $file = fopen($day, 'r');
    else
        throw new RuntimeException('invalid day');

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

function energize(array &$map, int $x, int $y, int $direction, int $fieldsDone = 0): void
{
    while (isset($map[$y][$x]) && !$map[$y][$x]['charged'][$direction])
    {
        print("$x\t $y\t - $fieldsDone / 48400\n");

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
        ++$fieldsDone;
    }
}
