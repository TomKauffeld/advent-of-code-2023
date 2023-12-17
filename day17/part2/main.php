<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'Graph.php';

const MAX_LENGTH = 10 + 1;
$map = readInputFile();
$h = count($map);
$w = count($map[0]);

$graph = \common\Graph::createFromData($map, MAX_LENGTH, 4);

$target = $graph->addNode($w, $h, 0, 0);
for ($d = -MAX_LENGTH; $d <= MAX_LENGTH; ++$d) {
    $graph->addEdge($graph->getNodeFromData($w - 1, $h - 1, $d, 0)->getId(), $target->getId(), 0);
    if ($d !== 0)
        $graph->addEdge($graph->getNodeFromData($w - 1, $h - 1, 0, $d)->getId(), $target->getId(), 0);
}

$cn = $graph->countNodes();
$ce = $graph->countEdges();
print("NODES: $cn\n");
print("EDGES: $ce\n");
$source = $graph->getNodeFromData(0, 0, 0, 0);

$path = $graph->dijkstra($source, $target, true);

var_dump($path);

$sum = 0;

foreach ($map as $y => $row)
{
    foreach ($row as $x => $weight)
    {
        $nodes = [];
        for ($d = -MAX_LENGTH; $d < MAX_LENGTH; ++$d)
        {
            $tmp = $graph->getNodeFromData($x, $y, $d, 0);
            if (in_array($tmp->getId(), $path['path']))
                $nodes[] = $tmp;

            if ($d !== 0)
            {
                $tmp = $graph->getNodeFromData($x, $y, 0, $d);
                if (in_array($tmp->getId(), $path['path']))
                    $nodes[] = $tmp;
            }
        }
        if (count($nodes) < 1)
            print($weight);
        else
        {
            $node = $nodes[0];
            if (count($nodes) > 1)
                throw new RuntimeException('More than one node ?');
            if ($x > 0 || $y > 0)
                $sum += $weight;
            if ($node->getDx() > 0)
                print('>');
            elseif($node->getDx() < 0)
                print('<');
            elseif($node->getDy() > 0)
                print('v');
            elseif($node->getDy() < 0)
                print('^');
            else
                print('+');
        }
    }
    print("\n");
}

print ("$sum\n");