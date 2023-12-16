<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'input.php';


$fields = getInput();


$sum = 0;


foreach ($fields as $field)
{
    $score = getScore($field);
    $sum += $score;
    print("$score\t - $sum\n");
}


function inversePosition(array $field, int $x, int $y): array
{
    $field[$y][$x] = !$field[$y][$x];
    return $field;
}

function isDifferent(array $reflectionsA, array $reflectionsB): bool
{
    $cxa = count($reflectionsA['x']);
    $cya = count($reflectionsA['y']);
    $cxb = count($reflectionsB['x']);
    $cyb = count($reflectionsB['y']);
    if ($cxa !== $cxb || $cya !== $cyb)
        return true;
    for ($x = 0; $x < $cxa && $x < $cxb; ++$x)
    {
        if ($reflectionsA['x'][$x] !== $reflectionsB['x'][$x])
            return true;
    }
    for ($y = 0; $y < $cya && $y < $cyb; ++$y)
    {
        if ($reflectionsA['y'][$y] !== $reflectionsB['y'][$y])
            return true;
    }
    return false;
}

function removeSameReflections(array $base, array $reflections): array
{
    $cx = count($reflections['x']);
    $cy = count($reflections['y']);
    for ($x = $cx - 1; $x >= 0; --$x)
    {
        if (in_array($reflections['x'][$x], $base['x']))
            array_splice($reflections['x'], $x, 1);
    }
    for ($y = $cy - 1; $y >= 0; --$y)
    {
        if (in_array($reflections['y'][$y], $base['y']))
            array_splice($reflections['y'], $y, 1);
    }
    return $reflections;
}

function getScore(array $field): int
{
    $h = count($field);
    $w = count($field[0]);
    $baseReflections = getReflections($field);
    $result = null;
    for ($x = 0; $x < $w; ++$x)
    {
        for ($y = 0; $y < $h; ++$y)
        {
            $field = inversePosition($field, $x, $y);
            $reflections = getReflections($field);
            $field = inversePosition($field, $x, $y);
            if (isDifferent($baseReflections, $reflections) && count($reflections['x']) + count($reflections['y']) > 0) {
                $result = $reflections;
                break;
            }
        }
    }
    if ($result === null)
        throw new RuntimeException('no reflections ?');
    $result = removeSameReflections($baseReflections, $result);

    $cx = count($result['x']);
    $cy = count($result['y']);
    if ($cx + $cy !== 1)
        throw new RuntimeException('invalid reflections ?');
    $sum = 0;
    foreach ($result['x'] as $x)
        $sum += $x;
    foreach ($result['y'] as $y)
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
