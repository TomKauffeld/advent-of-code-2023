<?php



function readInputFile(bool $test = false): array
{
    $file = getInputFile($test);
    $map = [];

    while(($line = fgets($file)) !== false)
    {
        $line = trim($line);
        $sLine = strlen($line);
        if ($sLine < 1)
            continue;
        $row = [];
        for ($x = 0; $x < $sLine; ++$x)
            $row[] = intval($line[$x]);
        $map[] = $row;
    }

    fclose($file);

    return $map;
}