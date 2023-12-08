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
 * @param int $day
 * @return resource
 */
function getInputFile(int $day) {
    $dayName = 'day' . str_pad("$day", 2, '0', STR_PAD_LEFT);
    $pathParts = [__DIR__, '..', 'advent-of-code-2023-data', $dayName, 'input.txt'];
    $path = join(DIRECTORY_SEPARATOR, $pathParts);
    $file = fopen($path, 'r');
    if ($file === false)
        throw new RuntimeException('cannot open file');
    return $file;
}
