<?php
namespace Aoc\Y2023\Day23\Common;

use RuntimeException;

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

function parseFile(bool $test = false): Map
{
    $charToDataMap = [
        '.' => Map::PATH,
        '#' => Map::WALL,
        '>' => Map::SLOPE_R,
        '<' => Map::SLOPE_L,
        '^' => Map::SLOPE_U,
        'v' => Map::SLOPE_D,
    ];

    $file = getInputFile($test);
    $data = [];

    $width = -1;
    $height = 0;
    while (($line = fgets($file)) !== false)
    {
        $line = trim($line);
        if (empty($line)) {
            continue;
        }
        ++$height;
        if ($width < 0)
            $width = strlen($line);
        elseif ($width !== strlen($line))
            throw new RuntimeException('Invalid format');

        for($i = 0; $i < $width; ++$i)
        {
            if (!isset($charToDataMap[$line[$i]]))
                throw new RuntimeException('Unexpected char');
            $data[] = $charToDataMap[$line[$i]];
        }
    }
    fclose($file);

    return new Map($data, $width, $height);
}
