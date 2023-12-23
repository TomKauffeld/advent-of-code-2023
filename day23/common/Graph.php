<?php

namespace Aoc\Y2023\Day23\Common;

use RuntimeException;

class Graph
{
    private array $nodes = [];
    private array $edges = [];
    private array $reverseEdges = [];

    public function getAmountOfNodes(): int
    {
        return count($this->nodes);
    }

    public function addNode(Point $point): int
    {
        $id = $point->hash();
        $this->nodes[$id] = $point;
        return $id;
    }

    public function getNode(int $id): ?Point
    {
        return $this->nodes[$id] ?? null;
    }

    public function addEdge(int $idFrom, int $idTo, float $weight)
    {
        if (!isset($this->edges[$idFrom]))
            $this->edges[$idFrom] = [];
        $this->edges[$idFrom][$idTo] = $weight;

        if (!isset($this->reverseEdges[$idTo]))
            $this->reverseEdges[$idTo] = [];
        $this->reverseEdges[$idTo][$idFrom] = $weight;
    }

    public function getPathsTo(int $fromId, int $toId, int $maxDepth, int ...$without): array
    {
        if ($maxDepth < 0)
            throw new RuntimeException('Max Depth exceeded');
        $paths = [];
        $edges = $this->reverseEdges[$toId] ?? [];
        foreach ($edges as $id => $weight)
        {
            if (in_array($id, $without))
                continue;
            if ($id === $fromId) {
                $paths[] = [
                    'path' => [$this->getNode($fromId)],
                    'weight' => $weight,
                ];
            } else {
                $subPaths = $this->getPathsTo($fromId, $id, $maxDepth - 1, $toId, ...$without);
                foreach ($subPaths as $path)
                {
                    $path['weight'] += $weight;
                    $path['path'][] = $this->getNode($id);
                    $paths[] = $path;
                }
            }
        }
        return $paths;
    }


}