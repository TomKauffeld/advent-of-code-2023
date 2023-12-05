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
