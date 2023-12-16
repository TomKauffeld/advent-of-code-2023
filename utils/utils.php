<?php

if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool {
        $lh = strlen($haystack);
        $ln = strlen($needle);
        if ($ln > $lh)
            return false;
        $sh = substr($haystack, 0, $ln);
        return $sh === $needle;
    }
}

if (!function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool {
        $lh = strlen($haystack);
        $ln = strlen($needle);
        if ($ln > $lh)
            return false;
        $sh = substr($haystack, $lh - $ln, $ln);
        return $sh === $needle;
    }
}

/**
 * @param string $str
 * @param string $separator
 * @param int $offset
 * @return int[]
 */
function getNumbers(string $str, string $separator = ' ', int $offset = 0): array
{
    if ($offset > 0)
        $str = substr($str, $offset);
    $numbers = [];
    $parts = explode($separator, $str);
    foreach ($parts as $part)
    {
        $part = trim($part);
        if (strlen($part) > 0 && is_numeric($part))
            $numbers[] = intval($part);
    }
    return $numbers;
}

/**
 * @param resource $file
 * @param string $prefix
 * @param string $separator
 * @return int[]
 */
function getNumbersFromFile($file, string $prefix, string $separator = ' '): array
{
    if (($line = fgets($file)) === false || !str_starts_with($line, $prefix))
        throw new RuntimeException('Invalid file format');
    return getNumbers($line, $separator, strlen($prefix));
}


/**
 * @param bool $test
 * @return resource
 */
function getInputFile(bool $test = false) {
    $stackTrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    $mainFile = $stackTrace[count($stackTrace) - 1]['file'];
    $mainFileParts = explode(DIRECTORY_SEPARATOR, $mainFile);
    $callingDirectoryParts = array_slice($mainFileParts, 0, count($mainFileParts) - 1);
    $partDirectoryParts = $callingDirectoryParts;

    while (!preg_match('/^part[0-9]+$/', $partDirectoryParts[count($partDirectoryParts) - 1]))
    {
        $partDirectoryParts = array_slice($partDirectoryParts, 0, count($partDirectoryParts) - 1);
        if (count($partDirectoryParts) < 1)
            throw new RuntimeException('invalid folder structure');
    }
    $dayName = $partDirectoryParts[count($partDirectoryParts) - 2];
    $partName = $partDirectoryParts[count($partDirectoryParts) - 1];

    $pathTest1 = join(DIRECTORY_SEPARATOR, [__DIR__, '..', $dayName, $partName, 'test.txt']);
    $pathTest2 = join(DIRECTORY_SEPARATOR, [__DIR__, '..', $dayName, 'test.txt']);
    $pathInput = join(DIRECTORY_SEPARATOR, [__DIR__, '..', 'advent-of-code-2023-data', $dayName, 'input.txt']);


    if ($test && file_exists($pathTest1))
        $filePath = $pathTest1;
    elseif ($test && file_exists($pathTest2))
        $filePath = $pathTest2;
    elseif ($test)
        throw new RuntimeException('no test file found');
    elseif (file_exists($pathInput))
        $filePath = $pathInput;
    else
        throw new RuntimeException('no input file found');

    $file = fopen($filePath, 'r');
    if ($file === false)
        throw new RuntimeException('cannot open file');
    return $file;
}