<?php
namespace Aoc\Y2023\Day22\Common;

use RuntimeException;

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

function pointRegex(string $name): string
{
    // In my code Y is the up direction and X,Z horizontal
    // So we flip the Z and Y directions for the input
    return "(?<{$name}x>\d+),(?<{$name}z>\d+),(?<{$name}y>\d+)";
}

function regex(): string
{
    return '/^' .pointRegex('s') . '~' . pointRegex('e') . '$/';
}

function parseFile(bool $test = false): Map
{
    $file = getInputFile($test);
    $map = new Map();
    $regex = regex();

    while (($line = fgets($file)) !== false)
    {
        $line = trim($line);
        if (empty($line)) {
            continue;
        }
        if (preg_match($regex, $line, $matches))
        {
            $start = new Point(intval($matches['sx']),intval($matches['sy']),intval($matches['sz']));
            $end = new Point(intval($matches['ex']),intval($matches['ey']),intval($matches['ez']));
            $brick = new Brick($start, $end);
            $map->addBrick($brick);
        }
    }
    fclose($file);

    $map->addGravity();

    return $map;
}
