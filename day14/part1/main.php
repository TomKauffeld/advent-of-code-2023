<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'input.php';


$field = getInput();

$h = count($field);
$w = count($field[0]);
$sum = 0;
$offsets = array_fill(0, $w, 0);

for ($y = 0; $y < $h; ++$y)
{
    $per = round($y * 100 / ($h - 1));
    print("$y / $h -> $per%\n");
    for ($x = 0; $x < $w; ++$x)
    {
        if ($field[$y][$x] === 2)
            $offsets[$x] = $y + 1;
        elseif ($field[$y][$x] === 1)
        {
            $weight = $h - $offsets[$x];
            ++$offsets[$x];
            $sum += $weight;
        }
    }
}

print ("Total weight: $sum\n");
