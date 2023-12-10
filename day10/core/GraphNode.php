<?php

class GraphNode
{
    private int $x;
    private int $y;
    private Graph $graph;
    private int $id;
    public array $data = [];

    public function __construct(Graph $graph, int $id, int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
        $this->id = $id;
        $this->graph = $graph;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getConnectedNodes(): array
    {
        return $this->graph->getConnectedNodes($this->getId());
    }

}
