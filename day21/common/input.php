<?php
namespace Aoc\Y2023\Day21\Common;

use RuntimeException;

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

function parseFile(bool $test = false): ParsedFile
{
    $file = getInputFile($test);
    $data = [];
    $startingPoint = ['x' => -1, 'y' => -1];

    $y = 0;
    while (($line = fgets($file)) !== false)
    {
        $line = trim($line);
        if (empty($line)) {
            continue;
        }
        $sLine = strlen($line);
        $row = [];
        for ($x = 0; $x < $sLine; ++$x)
        {
            switch ($line[$x])
            {
                /** @noinspection PhpMissingBreakStatementInspection */
                case 'S':
                    $startingPoint['x'] = $x;
                    $startingPoint['y'] = $y;
                case '.':
                    $row[$x] = false;
                    break;
                case '#':
                    $row[$x] = true;
                    break;
                default:
                    throw new RuntimeException('Invalid format');
            }
        }
        $data[$y] = $row;
        ++$y;
    }
    fclose($file);

    if ($startingPoint['x'] < 0 || $startingPoint['y']  < 0)
        throw new RuntimeException('Invalid format');

    $result = new ParsedFile();
    $result->map = new Map($data);
    $result->start = new Point($startingPoint['x'], $startingPoint['y']);

    return $result;
}
