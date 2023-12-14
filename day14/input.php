<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

/**
 * @param string|int $day
 * @return array
 */
function getInput($day): array
{
    if (is_int($day))
        $file = getInputFile($day);
    elseif (is_string($day))
        $file = fopen($day, 'r');
    else
        throw new InvalidArgumentException('day must be a filename or a day number');



    fclose($file);

    return [];
}
