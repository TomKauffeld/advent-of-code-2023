<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';


$file = getInputFile();


$currentHash = 0;
$sum = 0;
while (($char = fgetc($file)) !== false)
{
    switch ($char)
    {
        case ',':
            $sum += $currentHash;
            print("$currentHash\t - $sum\n");
            $currentHash = 0;
            break;
        case "\r":
        case "\n":
            break;
        default:
            $currentHash = holiday_hash($char, $currentHash);
            break;
    }
}
fclose($file);
if ($currentHash > 0)
{
    $sum += $currentHash;
    print("$currentHash\t - $sum\n");
}

function holiday_hash(string $input, int $initialValue = 0): int
{
    $hash = $initialValue % 256;
    $sInput = strlen($input);
    for ($i = 0; $i < $sInput; ++$i)
    {
        $hash += ord($input[$i]);
        $hash *= 17;
        $hash %= 256;
    }
    return $hash;
}
