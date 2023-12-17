<?php

namespace common;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Graph.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'GraphNode.php';

class GraphEdge
{
    private int $from;
    private int $to;
    private int $cost;
    private Graph $graph;

    public function __construct(Graph $graph, int $from, int $to, int $cost)
    {
        $this->from = $from;
        $this->to = $to;
        $this->cost = $cost;
        $this->graph = $graph;
    }

    public function getFrom(): GraphNode
    {
        return $this->getGraph()->getNode($this->getFromId());
    }

    public function getTo(): GraphNode
    {
        return $this->getGraph()->getNode($this->getToId());
    }

    public function getFromId(): int
    {
        return $this->from;
    }

    public function getToId(): int
    {
        return $this->to;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function getGraph(): Graph
    {
        return $this->graph;
    }
}