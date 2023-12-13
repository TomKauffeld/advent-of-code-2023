<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

/**
 * @param string|int $day
 * @return array
 */
function getInput($day): array
{
    if (is_int($day))
        $file = getInputFile(13);
    elseif (is_string($day))
        $file = fopen($day, 'r');
    else
        throw new InvalidArgumentException('day must be a filename or a day number');

    $fields = [];

    $currentField = null;
    while (($line = fgets($file)) !== false)
    {
        $line = trim($line);
        if (strlen($line) < 1)
        {
            if ($currentField !== null)
                $fields[] = $currentField;
            $currentField = null;
            continue;
        }
        $currentLine = [];
        $sLine = strlen($line);
        for ($i = 0; $i < $sLine; ++$i) {
            switch ($line[$i]) {
                case '.':
                    $currentLine[$i] = false;
                    break;
                case '#':
                    $currentLine[$i] = true;
                    break;
                default:
                    throw new RuntimeException("invalid char: $line[$i]");
            }
        }
        if ($currentField === null) {
            $currentField = [];
        } else {
            $sl = count($currentField[0]);
            $sf = count($currentLine);
            if ($sl !== $sf)
                throw new RuntimeException('invalid field size');
        }
        $currentField[] = $currentLine;

    }

    if ($currentField !== null)
        $fields[] = $currentField;
    $currentField = null;

    fclose($file);

    return $fields;
}
