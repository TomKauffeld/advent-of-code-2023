<?php
namespace Aoc\Y2023\Day23\Part1;

use Aoc\Y2023\Day23\Common\Point;
use function Aoc\Y2023\Day23\Common\parseFile;

require __DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'include.php';

$map = parseFile();

$from = null;
$to = null;
for ($x = 0; $x < $map->getWidth(); ++$x)
{
    if ($map->isPath(new Point($x, 0)))
        $from = new Point($x, 0);
    if ($map->isPath(new Point($x, $map->getHeight() - 1)))
        $to = new Point($x, $map->getHeight() - 1);
}


$graph = $map->toGraph();


$maxPath = null;
$paths = $graph->getPathsTo($from->hash(), $to->hash(), $graph->getAmountOfNodes());
foreach ($paths as $path)
{
    if ($maxPath === null || $path['weight'] > $maxPath['weight'])
        $maxPath = $path;
}

var_dump($maxPath);