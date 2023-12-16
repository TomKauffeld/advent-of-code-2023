<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

function getInput(bool $test = false): array
{
    $file = getInputFile($test);

    $field = [];
    $width = null;

    $caseMap = [
        '.' => 0,
        'O' => 1,
        '#' => 2,
    ];

    while(($line = fgets($file)) !== false)
    {
        $line = trim($line);
        $sLine = strlen($line);
        if ($sLine < 1)
            continue;
        if ($width !== null && $sLine !== $width)
            throw new RuntimeException('invalid map width');
        if ($width === null)
            $width = $sLine;

        $row = [];

        for ($x = 0; $x < $width; ++$x)
        {
            if (!isset($caseMap[$line[$x]]))
                throw new RuntimeException('invalid char');
            else
                $row[$x] = $caseMap[$line[$x]];
        }
        $field[] = $row;
    }


    fclose($file);

    return $field;
}
