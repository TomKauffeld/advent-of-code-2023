<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'include.php';

$commands = getCommands();

$polygon = createPolygon(...$commands);

$area = $polygon->getArea();
$perimeter = $polygon->getPerimeter();

$totalArea = $area + $perimeter / 2 + 1;


$maxNumber = max($area, $perimeter, $totalArea);
$maxDigits = strlen($maxNumber);
$perimeter = str_pad($perimeter, $maxDigits, " ", STR_PAD_LEFT);
$area = str_pad($area, $maxDigits, " ", STR_PAD_LEFT);
$totalArea  = str_pad($totalArea, $maxDigits, " ", $totalArea);
print("Perimeter:  $perimeter\n");
print("Area found: $area\n");
print("Total Area: $totalArea\n");
