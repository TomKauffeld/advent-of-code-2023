<?php
namespace Aoc\Y2023\Day24\Common;

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

function parseFile(bool $test = false): Map
{

    $file = getInputFile($test);
    $data = [];
    while (($line = fgets($file)) !== false)
    {
        $line = trim($line);
        if (empty($line)) {
            continue;
        }
        if (preg_match('/^(?<x>-?\d+), (?<y>-?\d+), (?<z>-?\d+) @ (?<dx>-?\d+), (?<dy>-?\d+), (?<dz>-?\d+)$/', $line, $matches))
        {
            $p = new Vector3(intval($matches['x']), intval($matches['y']), intval($matches['z']));
            $d = new Vector3(intval($matches['dx']), intval($matches['dy']), intval($matches['dz']));
            $data[] = new Hailstone($p, $d);
        }
    }
    fclose($file);

    return new Map($data);
}
