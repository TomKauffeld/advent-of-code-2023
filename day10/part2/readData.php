<?php


function getGraph(bool $test = false): Graph
{
    if (file_exists('graph.dat') && !$test) {
        return unserialize(file_get_contents('graph.dat'));
    }
    if ($test)
        $graph = FileParser::parseFile(fopen('test.txt', 'r'));
    else
        $graph = FileParser::parseInputFile(10);
    $startingNodes = FileParser::getStartingNodes($graph);
    if (count($startingNodes) !== 1) {
        throw new RuntimeException('invalid starting locations');
    }
    $component = $graph->getComponent($startingNodes[0]->getId(), true);
    $graph = $graph->getNewGraphFromIds($component);
    if (!$test)
        file_put_contents('graph.dat', serialize($graph));
    return $graph;
}

function getCycle(bool $test = false): Graph
{
    if (file_exists('cycle.dat') && !$test) {
        return unserialize(file_get_contents('cycle.dat'));
    }
    $graph = getGraph($test);
    $startingNodes = FileParser::getStartingNodes($graph);
    if (count($startingNodes) !== 1) {
        throw new RuntimeException('invalid starting locations');
    }
    $cycle = $graph->getCycle($startingNodes[0]->getId(), true);
    $graph = $graph->getNewGraphFromIds($cycle);
    if (!$test)
        file_put_contents('cycle.dat', serialize($graph));
    return $graph;
}
