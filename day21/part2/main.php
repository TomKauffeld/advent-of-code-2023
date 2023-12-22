<?php
namespace Aoc\Y2023\Day21\Part2;

use Aoc\Y2023\Day21\Common\Map2;
use Aoc\Y2023\Day21\Common\Point;
use RuntimeException;
use function Aoc\Y2023\Day21\Common\parseFile;

require __DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'include.php';

const TEST = true;

const STEPS = TEST ? 3 * 5 - 2: 26501365;

$file = parseFile(TEST);
$map = new Map2($file->map);


if ($map->getWidth() !== $map->getHeight())
    throw new RuntimeException('Invalid map');
if ($file->start->getX() !== $file->start->getY())
    throw new RuntimeException('Invalid map');
if ($file->start->getX() !== ($file->map->getWidth() - 1) / 2)
    throw new RuntimeException('Invalid map');

if ((STEPS - $file->start->getX()) % $map->getWidth() !== 0)
    throw new RuntimeException('Invalid map');

$maps = (STEPS - $file->start->getX()) / $map->getWidth();

$map->createExploreMap(STEPS, $file->start);

$far = $map->getWidth() - 1;
$mid = $file->start->getX();

$evenMaps = $map->countExplored($maps % 2 !== 0);
$amountEvenMaps = ($maps - 1) * ($maps - 1);
print("Even maps:            $evenMaps * $amountEvenMaps\n");

$oddMaps = $map->countExplored($maps % 2 === 0);
$amountOddMaps = $maps * $maps;
print("Odd maps:             $oddMaps * $amountOddMaps\n");

$top = $map->countReachableFrom($map->getWidth(), new Point($mid, $far), $maps % 2 !== 0);
print("Top:                  $top * 1\n");

$bottom = $map->countReachableFrom($map->getWidth(), new Point($mid, 0), $maps % 2 !== 0);
print("Bottom:               $bottom * 1\n");

$left = $map->countReachableFrom($map->getWidth(), new Point($far, $mid), $maps % 2 !== 0);
print("Left:                 $left * 1\n");

$right = $map->countReachableFrom($map->getWidth(), new Point(0, $mid), $maps % 2 !== 0);
print("Right:                $right * 1\n");

$amountInside = $maps - 1;
$iSteps = 3 * $far / 2;

$tli = $map->countReachableFrom($iSteps, new Point($far, $far), $maps % 2 !== 0);
print("Top Left Inside:      $tli * $amountInside\n");

$tri = $map->countReachableFrom($iSteps, new Point(0, $far), $maps % 2 !== 0);
print("Top Right Inside:     $tri * $amountInside\n");

$bli = $map->countReachableFrom($iSteps, new Point($far, 0), $maps % 2 !== 0);
print("Bottom Left Inside:   $bli * $amountInside\n");

$bri = $map->countReachableFrom($iSteps, new Point(0, 0), $maps % 2 !== 0);
print("Bottom Right Inside:  $bri * $amountInside\n");


$oSteps = $far / 2 - 1;
$amountOutside = $maps;

$tlo = $map->countReachableFrom($oSteps, new Point($far, $far), $maps % 2 === 0);
print("Top Left Outside:     $tlo * $amountOutside\n");

$tro = $map->countReachableFrom($oSteps, new Point(0, $far), $maps % 2 === 0);
print("Top Right Outside:    $tro * $amountOutside\n");

$blo = $map->countReachableFrom($oSteps, new Point($far, 0), $maps % 2 === 0);
print("Bottom Left Outside:  $blo * $amountOutside\n");

$bro = $map->countReachableFrom($oSteps, new Point(0, 0), $maps % 2 === 0);
print("Bottom Right Outside: $bro * $amountOutside\n");


$total = $oddMaps * $amountOddMaps +
    $evenMaps * $amountEvenMaps +
    $left +
    $right +
    $top +
    $bottom +
    $amountInside * ($tli + $tri + $bli + $bri) +
    $amountOutside * ($tlo + $tro + $blo + $bro);
print("Total: $total\n");

/// 620348637777579 is High
/// 620348631910833
/// 620348602981421 is Low