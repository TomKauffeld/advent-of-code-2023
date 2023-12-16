<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'input.php';


$field = getInput();

function fieldToKey(array $field): string
{
    $parts = [];
    foreach ($field as $row)
        $parts[] = join('', $row);
    return join('-', $parts);
}

$start = microtime(true);
$nextPrint = $start + 5;


$fields = [];

for ($i = 0; $i < 1_000_000_000; ++$i)
{
    $now = microtime(true);
    if ($nextPrint < $now) {
        $timePerI = ($now - $start) / $i;
        $todo = 1_000_000_000 - $i;
        $timeToDo = $timePerI * $todo;
        $hours = floor($timeToDo / 3600);
        $minutes = floor(($timeToDo - $hours * 3600) / 60);
        $seconds = round($timeToDo - $hours * 3600 - $minutes * 60);
        $per = round($i * 100 / (1_000_000_000 - 1));
        print ("$i / 1 000 000 000 -> $per% : $hours:$minutes:$seconds\n");
        $nextPrint += 5;
    }
    $key = fieldToKey($field);
    if (isset($fields[$key])) {
        $cycle = $i - $fields[$key];
        $todo = 1_000_000_000 - $i - 1;
        $cycles = floor($todo / $cycle);
        if ($cycles > 0) {
            $skip = $cycles * $cycle;
            $newI = $skip + $i;
            print ("Cycle from $fields[$key] to $i, skipping $skip to $newI\n");
            $i = $newI;
        }
    }
    else {
        $fields[$key] = $i;
    }
    cycle($field);
}

$score = calculateScore($field);
print ("Total Load: $score\n");


function calculateScore(array $field)
{
    $h = count($field);
    $w = count($field[0]);
    $sum = 0;

    for ($y = 0; $y < $h; ++$y)
    {
        for ($x = 0; $x < $w; ++$x)
        {
            if ($field[$y][$x] === 1)
            {
                $weight = $h - $y;
                $sum += $weight;
            }
        }
    }
    return $sum;
}

function cycle(array &$field): void
{
    shiftNorth($field);
    shiftWest($field);
    shiftSouth($field);
    shiftEast($field);
}



function shiftNorth(array &$field): void
{
    $h = count($field);
    $w = count($field[0]);
    $offsets = array_fill(0, $w, 0);

    for ($y = 0; $y < $h; ++$y)
    {
        for ($x = 0; $x < $w; ++$x)
        {
            if ($field[$y][$x] === 2)
                $offsets[$x] = $y + 1;
            elseif ($field[$y][$x] === 1)
            {
                $field[$y][$x] = 0;
                $field[$offsets[$x]][$x] = 1;
                ++$offsets[$x];
            }
        }
    }
}
function shiftSouth(array &$field): void
{
    $h = count($field);
    $w = count($field[0]);
    $offsets = array_fill(0, $w, $h - 1);

    for ($y = $h - 1; $y >= 0; --$y)
    {
        for ($x = 0; $x < $w; ++$x)
        {
            if ($field[$y][$x] === 2)
                $offsets[$x] = $y - 1;
            elseif ($field[$y][$x] === 1)
            {
                $field[$y][$x] = 0;
                $field[$offsets[$x]][$x] = 1;
                --$offsets[$x];
            }
        }
    }
}

function shiftWest(array &$field): void
{
    $h = count($field);
    $w = count($field[0]);
    $offsets = array_fill(0, $h, 0);

    for ($y = 0; $y < $h; ++$y)
    {
        for ($x = 0; $x < $w; ++$x)
        {
            if ($field[$y][$x] === 2)
                $offsets[$y] = $x + 1;
            elseif ($field[$y][$x] === 1)
            {
                $field[$y][$x] = 0;
                $field[$y][$offsets[$y]] = 1;
                ++$offsets[$y];
            }
        }
    }
}

function shiftEast(array &$field): void
{
    $h = count($field);
    $w = count($field[0]);
    $offsets = array_fill(0, $h, $w - 1);

    for ($y = 0; $y < $h; ++$y)
    {
        for ($x = $w - 1; $x >= 0; --$x)
        {
            if ($field[$y][$x] === 2)
                $offsets[$y] = $x - 1;
            elseif ($field[$y][$x] === 1)
            {
                $field[$y][$x] = 0;
                $field[$y][$offsets[$y]] = 1;
                --$offsets[$y];
            }
        }
    }
}
