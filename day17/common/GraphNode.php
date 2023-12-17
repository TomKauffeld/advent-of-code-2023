<?php

namespace common;

use RuntimeException;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'Point.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Graph.php';

class GraphNode extends Point
{
    private int $dx;
    private int $dy;
    private int $id;
    private Graph $graph;
    private array $edges = [];

    public function __construct(Graph $graph, int $id, int $x, int $y, int $dx, int $dy)
    {
        parent::__construct($x, $y);
        $this->dx = $dx;
        $this->dy = $dy;
        $this->graph = $graph;
        $this->id = $id;
    }

    public function getDx(): int
    {
        return $this->dx;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getGraph(): Graph
    {
        return $this->graph;
    }

    public function getDy(): int
    {
        return $this->dy;
    }

    public function addEdge(GraphEdge $edge): void
    {
        if ($edge->getFromId() !== $this->getId())
            throw new RuntimeException('invalid');
        $this->edges[] = $edge;
    }

    /** @return GraphEdge[] */
    public function getEdges(): array
    {
        return $this->edges;
    }

}