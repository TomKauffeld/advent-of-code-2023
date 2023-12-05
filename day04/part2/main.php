<?php

$file = fopen(join(DIRECTORY_SEPARATOR, ['..', 'data', 'input.txt']), 'r');

if (!$file)
    throw new RuntimeException('file not opened');

$cards = 0;

$extra = [];

while (($line = fgets($file)) !== false) {
    $line = trim($line);
    if (preg_match('/^Card +(?<game>[0-9]+):(?<winning>( +[0-9]+)+) \|(?<have>( +[0-9]+)+) *$/', $line, $matches)) {
        print("Card ${matches['game']}");
        $game = intval($matches['game']);

        $winning = getNumbers($matches['winning']);
        $have = getNumbers($matches['have']);

        $aWinning = count($winning);
        $aHave = count($have);

        print("\t - $aWinning\t - $aHave");

        $haveWinning = array_intersect($winning, $have);
        $aHaveWinning = count($haveWinning);
        print("\t - $aHaveWinning");

        $total = 1;
        if (isset($extra[$game])) {
            $total += $extra[$game];
            unset($extra[$game]);
        }

        for ($i = 0; $i < $aHaveWinning; ++$i) {
            $index = $game + $i + 1;
            $extra[$index] = $total + ($extra[$index] ?? 0);
        }

        $cards += $total;

        print("\t - $total\t - $cards\n");
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
