<?php

const MAX_PATH_LENGTH = 3;

function getIndex(array $point): string
{
    return join('-', $point);
}

function rebuiltPath(array $from, array $current): array
{
    $path = [$current];

    while (isset($from[$index = getIndex($current)]) && $from[$index] !== null)
    {
        $current = $from[$index];
        $path[] = $current;
    }

    return array_reverse($path);
}

function getSmallest(int $w, array &$points, array $scores): ?array
{
    usort($points, static function (array $a, array $b) use ($w, $scores): int {
        $direction = 1;
        $scoreA = getScore($scores, $a);
        $scoreB = getScore($scores, $b);
        if (is_infinite($scoreA))
            return is_infinite($scoreB) ? 0 : $direction;
        if (is_infinite($scoreB))
            return -$direction;

        return ($scoreA - $scoreB) * $direction;
    });

    return array_shift($points);
}

function getScore(array $scores, array $point): float
{
    return $scores[getIndex($point)] ?? INF;
}

function getBestScore(array $scores, array $point): float
{
    return getScore($scores, $point);
}

function setScore(array &$scores, array $point, float $score): void
{
    $scores[getIndex($point)] = $score;
    if ($point['dx'] >= 0)
        for($dx = $point['dx'] + 1; $dx < MAX_PATH_LENGTH - 1; ++$dx)
            $scores[getIndex(array_merge($point, ['dx' => $dx]))] = $score;
    if ($point['dx'] <= 0)
        for($dx = $point['dx'] - 1; $dx > -MAX_PATH_LENGTH + 1; --$dx)
            $scores[getIndex(array_merge($point, ['dx' => $dx]))] = $score;
    if ($point['dy'] >= 0)
        for($dy = $point['dy'] + 1; $dy < MAX_PATH_LENGTH - 1; ++$dy)
            $scores[getIndex(array_merge($point, ['dy' => $dy]))] = $score;
    if ($point['dy'] <= 0)
        for($dy = $point['dy'] - 1; $dy > -MAX_PATH_LENGTH + 1; --$dy)
            $scores[getIndex(array_merge($point, ['dy' => $dy]))] = $score;
}

function getCost(array $map, array $scores, array $from, array $to): float
{
    return getScore($scores, $from) + $map[$to['y']][$to['x']];
}

function search_AStar(array $map, array $start, array $end, callable $getHeuristic, callable $getNeighbors, callable $isEqual, callable $isEnd): array
{
    $h = count($map);
    $w = count($map[0]);
    $from = [];
    $todo = [$start];
    $scores = [];
    setScore($scores, $start, 0);
    $heuristics = [];
    $heuristics[getIndex($start)] = $getHeuristic($map, $start, $end);

    while(($current = getSmallest($w, $todo, $heuristics)) !== null)
    {
        if ($isEnd($current, $end))
            return [
                'cost' => $scores[getIndex($current)],
                'path' => rebuiltPath($from, $current),
            ];

        $neighbors = $getNeighbors($w, $h, $map, $current);

        foreach ($neighbors as $neighbor)
        {
            $neighborIndex = getIndex($neighbor);
            $tmpScore = getCost($map, $scores, $current, $neighbor);
            $score = getScore($scores, $neighbor);
            if ($tmpScore < $score)
            {
                $from[$neighborIndex] = $current;
                $scores[$neighborIndex] = $tmpScore;
                $heuristics[$neighborIndex] = $tmpScore + $getHeuristic($map, $neighbor, $end);

                insertIfNotExist($todo, $neighbor, $isEqual);
            }
        }
    }

    throw new RuntimeException('no path found');
}



function insertIfNotExist(array &$list, array $neighbor, callable $isEqual): void
{
    foreach ($list as $item)
        if ($isEqual($item, $neighbor))
            return;
    $list[] = $neighbor;
}