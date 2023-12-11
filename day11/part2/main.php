<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils.php';

$positions = readInputFile(11, 1000000 - 1);

$distanceMap = getDistanceMap($positions);


$sum = 0;
$cPositions = count($positions);
for($i = 0; $i < $cPositions; ++$i)
{
    for ($j = $i + 1; $j < $cPositions; ++$j)
    {
        $sum += $distanceMap[$i][$j] ?? 0;
    }
    print("$i / $cPositions - $sum\n");
}
