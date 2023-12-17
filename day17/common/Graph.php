<?php

namespace common;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'GraphEdge.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'GraphNode.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Priority.php';

use RuntimeException;

class Graph
{
    /** @var GraphNode[] */
    private array $nodes = [];
    /** @var GraphEdge[] */
    private array $edges = [];

    private array $map = [];


    public function countNodes(): int
    {
        return count($this->nodes);
    }

    public function countEdges(): int
    {
        return count($this->edges);
    }


    public function addNode(int $x, int $y, int $dx, int $dy): GraphNode
    {
        if ($this->doesNodeExist($x, $y, $dx, $dy))
            throw new RuntimeException('Node already exists');
        $id = count($this->nodes);
        $node = new GraphNode($this, $id, $x, $y, $dx, $dy);
        $this->nodes[$id] = $node;
        $this->map["$x:$y:$dx:$dy"] = $id;
        return $node;
    }

    public function getNode(int $id): GraphNode
    {
        return $this->nodes[$id];
    }

    public function addEdge(int $from, int $to, int $cost): GraphEdge
    {
        $edge = new GraphEdge($this, $from, $to, $cost);
        $this->getNode($from)->addEdge($edge);
        $this->edges[] = $edge;
        return $edge;
    }

    public function doesNodeExist(int $x, int $y, int $dx, int $dy): bool
    {
        return isset($this->map["$x:$y:$dx:$dy"]);
    }

    public function getNodeFromData(int $x, int $y, int $dx, int $dy): GraphNode
    {
        if ($this->doesNodeExist($x, $y, $dx, $dy))
            return $this->getNode($this->map["$x:$y:$dx:$dy"]);

        throw new RuntimeException("Node not found $x $y $dx $dy");
    }


    private static function getSmallest(array &$queue, array $dist): ?int
    {
        usort($queue, static function (int $a, int $b) use ($dist): int {
            if ($dist[$a] > $dist[$b])
                return 1;
            if ($dist[$a] < $dist[$b])
                return -1;
            return 0;
        });

        return array_shift($queue);
    }

    /**
     * @param GraphNode $source
     * @param GraphNode $target
     * @param bool $debug
     * @return array
     */
    public function dijkstra(GraphNode $source, GraphNode $target, bool $debug = false): array
    {
        $count = $this->countNodes();
        if ($debug) {
            $next = time() + 5;
            $done = 0;
        }

        $Q = new Priority();
        $dist = [];
        $prev = [];
        foreach ($this->nodes as $v => $node) {
            $dist[$v] = INF;
            $prev[$v] = null;
            $Q->push($v, PHP_INT_MAX);
        }
        $Q->change_priority($source->getId(), 0);
        $dist[$source->getId()] = 0;

        while (($u = $Q->pop()) !== null)
        {
            if (is_infinite($dist[$u])) {
                throw new RuntimeException('Cannot continue');
            }
            if ($debug)
            {
                ++$done;
                $now = time();
                if ($now > $next) {
                    $per = round(100 * $done / $count);
                    $next = $now + 5;
                    print("DONE : $per% $done / $count\n");
                }
            }
            if ($u === $target->getId())
            {
                $S = [$u];
                while (($u = $prev[$u]) !== null)
                    $S[] = $u;
                return [
                    'path' => array_reverse($S),
                    'dist' => $dist[$target->getId()],
                ];
            }

            $node = $this->getNode($u);
            foreach ($node->getEdges() as $edge)
            {
                $to = $edge->getTo();
                if ($Q->contains($to->getId()))
                {
                    $alt = $dist[$u] + $edge->getCost();
                    if ($alt < $dist[$edge->getToId()]) {
                        $dist[$edge->getToId()] = $alt;
                        $prev[$edge->getToId()] = $u;
                        $Q->change_priority($edge->getToId(), $alt);
                    }
                }
            }
        }
        throw new RuntimeException('Cannot find path');

    }


    public static function createFromData(array $map, int $maxLength, int $minLength = 0): Graph
    {
        $h = count($map);
        $w = count($map[0]);
        $graph = new Graph();
        foreach ($map as $y => $row)
        {
            print("$y\n");
            foreach ($row as $x => $weight)
            {
                for ($d = -$maxLength; $d <= $maxLength; ++$d)
                {
                    $graph->addNode($x, $y, $d, 0);
                    if ($d !== 0)
                        $graph->addNode($x, $y, 0, $d);
                }
            }
        }
        foreach ($map as $y => $row)
        {
            print("$y\n");
            foreach ($row as $x => $weight)
            {
                for ($d = -$maxLength; $d <= $maxLength; ++$d)
                {
                    $node = $graph->getNodeFromData($x, $y, $d, 0);
                    self::AddEdgesForNode($node, $maxLength, $map, $w, $h, $minLength);
                    if ($d !== 0)
                    {
                        $node = $graph->getNodeFromData($x, $y, 0, $d);
                        self::AddEdgesForNode($node, $maxLength, $map, $w, $h, $minLength);
                    }
                }
            }
        }
        return $graph;
    }

    private static function AddEdgesForNode(GraphNode $node, int $maxLength, array $weights, int $width, int $height, int $minLength = 0)
    {
        $nodes = [];

        if ($node->getX() < $width - 1 && $node->getDx() < $maxLength - 1 && $node->getDx() >= 0 && ($node->getDy() === 0 || abs($node->getDy()) >= $minLength))
            $nodes[] = $node->getGraph()->getNodeFromData($node->getX() + 1, $node->getY(), $node->getDx() + 1, 0);
        if ($node->getX() > 0 && $node->getDx() > -$maxLength + 1 && $node->getDx() <= 0 && ($node->getDy() === 0 || abs($node->getDy()) >= $minLength))
            $nodes[] = $node->getGraph()->getNodeFromData($node->getX() - 1, $node->getY(), $node->getDx() - 1, 0);
        if ($node->getY() < $height - 1 && $node->getDy() < $maxLength - 1 && $node->getDy() >= 0 && ($node->getDx() === 0 || abs($node->getDx()) >= $minLength))
            $nodes[] = $node->getGraph()->getNodeFromData($node->getX(), $node->getY() + 1, 0, $node->getDy() + 1);
        if ($node->getY() > 0 && $node->getDy() > -$maxLength + 1 && $node->getDy() <= 0 && ($node->getDx() === 0 || abs($node->getDx()) >= $minLength))
            $nodes[] = $node->getGraph()->getNodeFromData($node->getX(), $node->getY() - 1, 0, $node->getDy() - 1);


        foreach ($nodes as $n)
            $node->getGraph()->addEdge($node->getId(), $n->getId(), $weights[$n->getY()][$n->getX()]);
    }

}