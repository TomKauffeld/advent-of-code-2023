<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'include.php';


$graph = FileParser::parseFile(getInputFile());

$startingNodes = FileParser::getStartingNodes($graph);

if (count($startingNodes) !== 1)
    throw new RuntimeException('invalid starting locations');

$maxDistance = $graph->calculateDistances($startingNodes[0]->getId(), true);

print('MAX DISTANCE: ' . $maxDistance->data['distance'] . "\n");
