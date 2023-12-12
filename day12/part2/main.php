<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

//$file = getInputFile(12);
$file = fopen('test.txt', 'r');

$sum = 0;
while (($line = fgets($file)) !== false)
{
    $line = trim($line);
    $parts = explode(' ', $line);
    if (count($parts) !== 2)
        continue;
    $springs = $parts[0];
    $springs = "$springs?$springs?$springs?$springs?$springs";
    $damaged = getNumbers($parts[1], ',');
    $damaged = array_merge($damaged, $damaged, $damaged, $damaged, $damaged);
    //$possibles = getPossibles($springs, null, ...$damaged);
    //$cPossibles = count($possibles);
    $cPossibles = getPossiblesCount($springs, null, ...$damaged);
    $sum += $cPossibles;
    print ("$cPossibles\t - $sum\n");
}


fclose($file);



function getPossibles(string $springs, ?int $remaining = null, int ... $damaged): array
{
    $d = array_shift($damaged);
    if ($d === null || $d < 1) {
        $index = stripos($springs, '#');
        if ($index !== false)
            return [];
        return [str_replace('?', '.', $springs)];
    }

    if ($remaining === null)
    {
        $remaining = 0;
        foreach ($damaged as $i)
            $remaining += $i;
    } else {
        $remaining -= $d;
    }

    $sSprings = strlen($springs);
    $possibles = [];
    $iMax = $sSprings - $d - $remaining + 1;
    for ($i = 0; $i < $iMax; ++$i)
    {
        $ok = $i + $d === $sSprings || $springs[$i + $d] === '.' || $springs[$i + $d] === '?';
        $ok &= $i === 0 || $springs[$i - 1] === '.' || $springs[$i - 1] === '?';

        for ($j = $i; $j < $sSprings && $j < $i + $d && $ok; ++$j)
        {
            if ($springs[$j] !== '?' && $springs[$j] !== '#')
                $ok = false;
        }
        if ($ok)
        {
            if ($i + $d === $sSprings) {
                return [createPrefix($springs, $i, $d)];
            } else {
                $prefix = createPrefix($springs, $i, $d);
                $subSprings = substr($springs, $i + $d + 1);
                $subPossibles = getPossibles($subSprings, $remaining, ... $damaged);
                foreach ($subPossibles as $item)
                {
                    $possibles[] = "$prefix.$item";
                }
            }
        }
    }
    return $possibles;
}

function getPossiblesCount(string $springs, ?int $remaining = null, int ... $damaged): int
{
    $d = array_shift($damaged);
    if ($d === null || $d < 1) {
        $index = stripos($springs, '#');
        if ($index !== false)
            return 0;
        return 1;
    }

    if ($remaining === null)
    {
        $remaining = 0;
        foreach ($damaged as $i)
            $remaining += $i;
    } else {
        $remaining -= $d;
    }

    $sSprings = strlen($springs);
    $possibles = 0;
    $iMax = $sSprings - $d - $remaining + 1;
    for ($i = 0; $i < $iMax; ++$i)
    {
        $ok = $i + $d === $sSprings || $springs[$i + $d] === '.' || $springs[$i + $d] === '?';
        $ok &= $i === 0 || $springs[$i - 1] === '.' || $springs[$i - 1] === '?';

        for ($j = $i; $j < $sSprings && $j < $i + $d && $ok; ++$j)
        {
            if ($springs[$j] !== '?' && $springs[$j] !== '#')
                $ok = false;
        }
        if ($ok)
        {
            if ($i + $d === $sSprings) {
                return 1;
            } else {
                $subSprings = substr($springs, $i + $d + 1);
                $subPossibles = getPossiblesCount($subSprings, $remaining, ... $damaged);
                $possibles += $subPossibles;
            }
        }
    }
    return $possibles;
}

function createPrefix(string $springs, int $index, int $length): string
{
    $prefix = substr($springs, 0, $index + $length);
    $prefix = substr_replace($prefix, str_repeat('#', $length), $index);
    return str_replace('?', '.', $prefix);
}
