<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

$file = getInputFile();

$sum = 0;
while (($line = fgets($file)) !== false)
{
    $line = trim($line);
    $parts = explode(' ', $line);
    if (count($parts) !== 2)
        continue;
    $springs = $parts[0];
    $sSprings = strlen($springs);
    $damaged = getNumbers($parts[1], ',');
    $arrangements = getArrangements($springs, ...$damaged);
    $cArrangements = count($arrangements);
    $sum += $cArrangements;
    print ("$cArrangements\t - $sum\n");
}


fclose($file);


function isArrangementPossible(string $springs, int ...$damaged): bool
{
    $current = 0;
    $offset = 0;
    $sSprings = strlen($springs);
    $cDamaged = count($damaged);
    for ($i = 0; $i < $sSprings; ++$i)
    {
        switch ($springs[$i])
        {
            case '.':
                if ($current > 0) {
                    if ($current !== $damaged[$offset])
                        return false;
                    $current = 0;
                    ++$offset;
                }
                break;
            case '#':
                ++$current;
                if ($offset >= $cDamaged || $current > $damaged[$offset])
                    return false;
                break;
            default:
                throw new RuntimeException('invalid char: ' . $springs[$i]);
        }
    }
    if ($current > 0)
    {
        if ($offset !== $cDamaged - 1)
            return false;
        if ($current !== $damaged[$offset])
            return false;
    } else {
        if ($offset !== $cDamaged)
            return false;
    }
    return true;
}

function replaceChar(string $str, string $char, int $index): string
{
    $str = "$str";
    $str[$index] = $char;
    return $str;
}

function getArrangements(string $springs, int ...$damaged): array
{
    $index = stripos($springs, '?');
    if ($index !== false) {
        return array_merge(
            getArrangements(replaceChar($springs, '.', $index), ...$damaged),
            getArrangements(replaceChar($springs, '#', $index), ...$damaged),
        );
    } else {
        if (isArrangementPossible($springs, ...$damaged))
            return [$springs];
        return [];
    }
}

