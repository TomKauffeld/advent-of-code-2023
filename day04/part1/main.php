<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

$file = getInputFile(4);

if (!$file)
    throw new RuntimeException('file not opened');

$sum = 0;

while (($line = fgets($file)) !== false) {
    $line = trim($line);
    if (preg_match('/^Card +(?<game>[0-9]+):(?<winning>( +[0-9]+)+) \|(?<have>( +[0-9]+)+) *$/', $line, $matches)) {
        print("Card ${matches['game']}");

        $winning = getNumbers($matches['winning']);
        $have = getNumbers($matches['have']);

        $aWinning = count($winning);
        $aHave = count($have);

        print("\t - $aWinning\t - $aHave");

        $haveWinning = array_intersect($winning, $have);
        $aHaveWinning = count($haveWinning);
        print("\t - $aHaveWinning");

        $score = $aHaveWinning > 0 ? pow(2, $aHaveWinning - 1) : 0;

        $sum += $score;

        print("\t - $score\t - $sum\n");
    }
}

function getNumbers(string $text): array {
    $numbers = [];
    foreach (explode(' ', $text) as $item) {
        $trimmed = trim($item);
        if (strlen($trimmed) > 0 && is_numeric($trimmed)) {
            $numbers[] = intval($trimmed);
        }
    }
    return $numbers;
}

fclose($file);
