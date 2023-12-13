<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'input.php';


$fields = getInput(13);


$sum = 0;


foreach ($fields as $field)
{
    $score = getScore($field);
    $sum += $score;
    print("$score\t - $sum\n");
}



function getScore(array $field): int
{
    $reflections = getReflections($field);
    $cx = count($reflections['x']);
    $cy = count($reflections['y']);
    if ($cx + $cy !== 1)
        throw new RuntimeException('invalid reflections ?');
    $sum = 0;
    foreach ($reflections['x'] as $x)
        $sum += $x;
    foreach ($reflections['y'] as $y)
        $sum += $y * 100;
    return $sum;
}

function getReflections(array $field): array
{
    $h = count($field);
    $w = count($field[0]);
    $validX = [];
    $validY = [];
    for ($x = 1; $x < $w; ++$x)
    {
        if (isVerticalSymmetry($x, $field))
            $validX[] = $x;
    }
    for ($y = 1; $y < $h; ++$y)
    {
        if (isHorizontalSymmetry($y, $field))
            $validY[] = $y;
    }
    return ['x' => $validX, 'y' => $validY];
}

function isVerticalSymmetry(int $xPos, array $field): bool
{
    $h = count($field);
    $w = count($field[0]);
    if ($xPos < 1 || $xPos >= $w)
        throw new InvalidArgumentException('invalid xPos');

    for ($x = 0; $x < $xPos; ++$x)
    {
        $xl = $xPos - $x - 1;
        $xr = $xPos + $x;
        if ($xl < 0 || $xr >= $w)
            continue;

        for ($y = 0; $y < $h; ++$y)
        {
            if ($field[$y][$xl] !== $field[$y][$xr])
                return false;
        }
    }
    return true;
}

function isHorizontalSymmetry(int $yPos, array $field): bool
{
    $h = count($field);
    $w = count($field[0]);
    if ($yPos < 1 || $yPos >= $h)
        throw new InvalidArgumentException('invalid yPos');
    for ($y = 0; $y < $yPos; ++$y)
    {
        $yt = $yPos - $y - 1;
        $yb = $yPos + $y;
        if ($yt < 0 || $yb >= $h)
            continue;
        for ($x = 0; $x < $w; ++$x)
        {
            if ($field[$yt][$x] !== $field[$yb][$x])
                return false;
        }
    }
    return true;
}
