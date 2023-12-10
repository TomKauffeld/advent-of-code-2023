<?php


class FileParser
{

    public static function parseFile($file): Graph
    {
        $graph = new Graph();

        $y = 0;
        while (($line = fgets($file)) !== false)
        {
            $line = trim($line);
            $sLine = strlen($line);
            for ($x = 0; $x < $sLine; ++$x)
                self::parseNodeAt($graph, $x, $y, $line);
            ++$y;
        }

        fclose($file);

        $amountOfNodes = $graph->amountOfNodes();
        for($id = 0; $id < $amountOfNodes; ++$id)
            self::addVertices($graph, $id);

        return $graph;
    }

    public static function parseInputFile(int $day): Graph
    {
        $file = getInputFile($day);
        return self::parseFile($file);
    }

    /**
     * @param Graph $graph
     * @return GraphNode[]
     */
    public static function getStartingNodes(Graph $graph): array
    {
        $nodes = [];
        $amountOfNodes = $graph->amountOfNodes();
        for($id = 0; $id < $amountOfNodes; ++$id)
        {
            $node = $graph->getNode($id);
            if ($node !== null && isset($node->data['char']) && $node->data['char'] === 'S')
                $nodes[] = $node;
        }
        return $nodes;
    }



    public static function parseNodeAt(Graph $graph, int $x, int $y, string $line): ?GraphNode
    {
        switch ($line[$x] ?? null)
        {
            case '|':
            case '-':
            case 'L':
            case 'J':
            case '7':
            case 'F':
            case 'S':
                $node = $graph->addNode($x, $y);
                $node->data['char'] = $line[$x];
                return $node;
            default:
                return null;
        }
    }

    public static function addVertices(Graph $graph, int $id, bool $withStart = true): void
    {
        $node = $graph->getNode($id);
        $others = [
            self::getConnectionLeft($graph, $node, $withStart),
            self::getConnectionRight($graph, $node, $withStart),
            self::getConnectionTop($graph, $node, $withStart),
            self::getConnectionBottom($graph, $node, $withStart),
        ];
        foreach ($others as $other)
            if ($other !== null)
                $graph->addVertices($node->getId(), $other->getId());
    }

    public static function getConnectionLeft(Graph $graph, GraphNode $node, bool $withStart = true): ?GraphNode
    {
        if (!self::hasConnectionLeft($node, $withStart))
            return null;
        $other = $graph->getNodeAt($node->getX() - 1, $node->getY());
        if ($other !== null && self::hasConnectionRight($other, $withStart))
            return $other;
        return null;
    }

    public static function getConnectionRight(Graph $graph, GraphNode $node, bool $withStart = true): ?GraphNode
    {
        if (!self::hasConnectionRight($node, $withStart))
            return null;
        $other = $graph->getNodeAt($node->getX() + 1, $node->getY());
        if ($other !== null && self::hasConnectionLeft($other, $withStart))
            return $other;
        return null;
    }

    public static function getConnectionTop(Graph $graph, GraphNode $node, bool $withStart = true): ?GraphNode
    {
        if (!self::hasConnectionTop($node, $withStart))
            return null;
        $other = $graph->getNodeAt($node->getX(), $node->getY() - 1);
        if ($other !== null && self::hasConnectionBottom($other, $withStart))
            return $other;
        return null;
    }

    public static function getConnectionBottom(Graph $graph, GraphNode $node, bool $withStart = true): ?GraphNode
    {
        if (!self::hasConnectionBottom($node, $withStart))
            return null;
        $other = $graph->getNodeAt($node->getX(), $node->getY() + 1);
        if ($other !== null && self::hasConnectionTop($other, $withStart))
            return $other;
        return null;
    }

    public static function hasConnectionLeft(GraphNode $node, bool $withStart = true): bool
    {
        return self::hasConnection($node, ['-', '7', 'J'], $withStart);
    }

    public static function hasConnectionRight(GraphNode $node, bool $withStart = true): bool
    {
        return self::hasConnection($node, ['-', 'L', 'F'], $withStart);
    }

    public static function hasConnectionTop(GraphNode $node, bool $withStart = true): bool
    {
        return self::hasConnection($node, ['|', 'L', 'J'], $withStart);
    }

    public static function hasConnectionBottom(GraphNode $node, bool $withStart = true): bool
    {
        return self::hasConnection($node, ['|', '7', 'F'], $withStart);
    }

    private static function hasConnection(GraphNode $node, array $parts, bool $withStart = true): bool
    {
        if ($withStart)
            return in_array($node->data['char'], ['S', ... $parts]);
        return in_array($node->data['char'], $parts);
    }

    public static function hasConnectionTopOrBottom(GraphNode $node, bool $withStart = true): bool
    {
        return self::hasConnectionTop($node, $withStart) || self::hasConnectionBottom($node, $withStart);
    }

    public static function hasConnectionLeftOrRight(GraphNode $node, bool $withStart = true): bool
    {
        return self::hasConnectionLeft($node, $withStart) || self::hasConnectionRight($node, $withStart);
    }
}

