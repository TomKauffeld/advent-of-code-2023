<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'path.php';

$map = readInputFile(true);

$h = count($map);
$w = count($map[0]);

print ("Size: {$w}x$h\n");

$path = search_AStar(
    $map,
    ['x' => 0, 'y' => 0, 'dx' => 0, 'dy' => 0],
    ['x' => $w - 1, 'y' => $h - 1],
    'heuristic',
    'getNeighbors',
    'neighborEquals',
    'isEnd'
);
$cPath = count($path['path']);

print ("PathLength: $cPath\n");

$cost = 0;
for ($y = 0; $y < $h; ++$y)
{
    for ($x = 0; $x < $w; ++$x)
    {
        $point = getPointInPath($path['path'], $x, $y);
        if ($point === null)
            print($map[$y][$x]);
        else
        {
            if ($x !== 0 && $y !== 0)
                $cost += $map[$y][$x];
            if ($point['dx'] > 0)
                print(">");
            elseif($point['dx'] < 0)
                print("<");
            elseif($point['dy'] > 0)
                print("v");
            elseif($point['dy'] < 0)
                print("^");
            else
                print("+");
        }
    }
    print("\n");
}

function isEnd(array $current, array $end): bool
{
    return $current['x'] === $end['x'] && $current['y'] === $end['y'];
}

function getPointInPath(array $path, int $x, int $y): ?array
{
    foreach ($path as $point)
        if ($point['x'] === $x && $point['y'] === $y)
            return $point;
    return null;
}

print ("COST: {$path['cost']}\t$cost\n");


function getNeighbor(int $dx, int $dy, array $current): array
{
    return [
        'x' => $current['x'] + $dx,
        'y' => $current['y'] + $dy,
        'dx' => $dx === 0 ? 0 : $current['dx'] + $dx,
        'dy' => $dy === 0 ? 0 : $current['dy'] + $dy,
    ];
}

function getNeighbors(int $w, int $h, array $map, array $current): array
{
    $pathLimit = 3 - 1;
    $neighbors = [];
    if ($current['x'] > 0 && $current['dx'] > -$pathLimit && $current['dx'] <= 0)
        $neighbors[] = getNeighbor(-1, 0, $current);
    if ($current['x'] < $w - 1 && $current['dx'] < $pathLimit && $current['dx'] >= 0)
        $neighbors[] = getNeighbor(1, 0, $current);
    if ($current['y'] > 0 && $current['dy'] > -$pathLimit && $current['dy'] <= 0)
        $neighbors[] = getNeighbor(0, -1, $current);
    if ($current['y'] < $h - 1 && $current['dy'] < $pathLimit && $current['dy'] >= 0)
        $neighbors[] = getNeighbor(0, 1, $current);

    return $neighbors;
}

function neighborEquals(array $a, array $b): bool
{
    return $a['x'] === $b['x'] && $a['y'] === $b['y'] && $a['dx'] === $b['dx'] && $a['dy'] === $b['dy'];
}

function heuristic(array $map, array $pointA, array $end): float
{
    return (abs($pointA['x'] - $end['x']) + abs($pointA['y'] + $end['y']));
}