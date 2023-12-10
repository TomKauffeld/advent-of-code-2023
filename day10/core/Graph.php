<?php

class Graph
{
    private array $positionMap = [];
    /** @var GraphNode[] */
    private array $nodes = [];
    private array $vertices = [];

    public function __construct()
    {

    }

    public function addNode(int $x, int $y): GraphNode
    {
        $id = $this->amountOfNodes();

        $node = new GraphNode($this, $id, $x, $y);

        $this->nodes[] = $node;

        if (!isset($this->positionMap[$y]))
            $this->positionMap[$y] = [];

        $this->positionMap[$y][$x] = $id;

        $this->vertices[$id] = [];

        return $node;
    }

    public function addVertices(int $nodeA, int $nodeB): bool
    {
        $amountOfNodes = $this->amountOfNodes();
        if ($nodeA < 0 || $nodeB < 0 || $nodeA >= $amountOfNodes || $nodeB >= $amountOfNodes)
            return false;
        $this->vertices[$nodeA][$nodeB] = 1;
        $this->vertices[$nodeB][$nodeA] = 1;
        return true;
    }

    public function amountOfNodes(): int
    {
        return count($this->nodes);
    }

    public function getNode(int $id): ?GraphNode
    {
        return $this->nodes[$id] ?? null;
    }

    public function getNodeAt(int $x, int $y): ?GraphNode
    {
        if (isset($this->positionMap[$y][$x]))
            return $this->getNode($this->positionMap[$y][$x]);
        return null;
    }

    /**
     * @param int $id
     * @return GraphNode[]
     */
    public function getConnectedNodes(int $id): array
    {
        $connected = [];
        $iMax = $this->amountOfNodes();
        if ($id < 0 || $id >= $iMax)
            return [];
        for($i = 0; $i < $iMax; ++$i)
            if ($this->areNodesConnected($id, $i))
                $connected[] = $this->getNode($i);
        return $connected;
    }

    public function areNodesConnected(int $nodeA, int $nodeB): bool
    {
        return isset($this->vertices[$nodeA][$nodeB]) && $this->vertices[$nodeA][$nodeB] > 0;
    }

    public function verticesToCsv(string $path): bool
    {
        $file = fopen($path, 'w');
        if ($file === false)
            return false;
        $amountOfNodes = $this->amountOfNodes();
        for ($i = 0; $i < $amountOfNodes; ++$i)
        {
            $connected = [];
            for ($j = 0; $j < $amountOfNodes; ++$j)
                $connected[] = $this->areNodesConnected($i, $j) ? 1 : 0;
            fwrite($file, join(', ', $connected) . "\n");
        }

        fclose($file);
        return true;
    }

    /**
     * @param int[] $ids
     * @return Graph
     */
    public function getNewGraphFromIds(array $ids): Graph
    {
        $graph = new Graph();
        $map = [];

        foreach ($ids as $id) {
            $node = $this->getNode($id);
            $newNode = $graph->addNode($node->getX(), $node->getY());
            $newNode->data = $node->data;
            $newNode->data['old'] = $node;
            $map[$node->getId()] = $newNode->getId();
        }
        foreach ($ids as $id) {
            $connected = $this->getConnectedNodes($id);
            $newNodeId = $map[$id];
            foreach ($connected as $c) {
                if (isset($map[$c->getId()])) {
                    $newConnectedId = $map[$c->getId()];
                    $graph->addVertices($newNodeId, $newConnectedId);
                }
            }
        }

        return $graph;
    }


    /**
     * @param int[] $array
     * @return int|null
     */
    protected function getClosest(array &$array): ?int
    {
        /** @var GraphNode|null $minNode */
        $minNode = null;
        $minIndex = null;
        $index = 0;
        foreach ($array as $id) {
            $node = $this->getNode($id);
            if ($minNode == null || $minNode->data['distance'] > $node->data['distance']) {
                $minNode = $node;
                $minIndex = $index;
            }
            ++$index;
        }
        if ($minIndex === null)
            return null;
        array_splice($array, $minIndex, 1);
        return $minNode->getId();
    }

    public function getMaxDistance(): ?GraphNode
    {
        /** @var GraphNode|null $maxNode */
        $maxNode = null;

        foreach ($this->nodes as $node) {
            if (isset($node->data['distance']) && ($maxNode === null || $maxNode->data['distance'] < $node->data['distance']))
                $maxNode = $node;
        }
        return $maxNode;
    }

    public function calculateDistances(int $id, bool $debug = false): GraphNode
    {
        $largest = $this->getNode($id);
        $largest->data['distance'] = 0;
        $todo = [$id];
        /** @var int[] $done */
        $done = [];
        if ($debug)
            $a = $this->amountOfNodes();
        while (($nodeId = $this->getClosest($todo)) !== null)
        {
            if ($debug) {
                $d = count($done);
                $t = count($todo);
                print("$d / $a -> $t\n");
            }
            $connected = $this->getConnectedNodes($nodeId);
            $done[] = $nodeId;
            $node = $this->getNode($nodeId);
            foreach ($connected as $n) {
                if (!in_array($n->getId(), $done) && !in_array($n->getId(), $todo)) {
                    $n->data['parent'] = $nodeId;
                    $n->data['distance'] = $node->data['distance'] + 1;

                    if ($n->data['distance'] > $largest->data['distance'])
                        $largest = $n;

                    $todo[] = $n->getId();
                }
            }
        }

        return $largest;
    }

    /**
     * @param int $id
     * @param bool $debug
     * @return int[]
     */
    public function getComponent(int $id, bool $debug = false): array
    {
        $todo = [$id];
        /** @var int[] $done */
        $done = [];
        if ($debug)
            $a = $this->amountOfNodes();

        while (($nodeId = array_shift($todo)) !== null)
        {
            if ($debug) {
                $d = count($done);
                $t = count($todo);
                print("$d / $a -> $t\n");
            }
            $connected = $this->getConnectedNodes($nodeId);
            $done[] = $nodeId;
            foreach ($connected as $n)
                if (!in_array($n->getId(), $done) && !in_array($n->getId(), $todo))
                    $todo[] = $n->getId();
        }

        return $done;
    }

    public function getCycle(int $id, bool $debug): array
    {
        $largest = $this->calculateDistances($id, $debug);

        $loop[] = $largest->getId();

        $connected = $this->getConnectedNodes($largest->getId());

        foreach ($connected as $node)
        {
            while ($node !== null)
            {
                $nodeId = $node->getId();
                if ($debug) {
                    $distance = $node->data['distance'];
                    print("$nodeId -> $distance\n");
                }
                if (!in_array($nodeId, $loop))
                    $loop[] = $nodeId;
                if (isset($node->data['parent']))
                    $node = $this->getNode($node->data['parent']);
                else
                    $node = null;
            }
        }

        return $loop;
    }
}
