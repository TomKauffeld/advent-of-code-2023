<?php
require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

function parseFile(bool $test = false): ParsedFile
{
    $mode = 0;
    $file = getInputFile($test);
    $result = new ParsedFile();

    while (($line = fgets($file)) !== false)
    {
        $line = trim($line);
        if (empty($line)) {
            ++$mode;
            continue;
        }

        switch ($mode)
        {
            case 0:
                $result->workflows[] = Workflow::Parse($line);
                break;
            case 1:
                $result->inputs[] = InputSet::Parse($line);
                break;
        }
    }
    fclose($file);

    return $result;
}
