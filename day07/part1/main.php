<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

$file = getInputFile();

$sets = [];

print("Reading file\n");

while (($line = fgets($file)) !== false)
{
    if (preg_match('/^(?<hand>[123456789TJQKA]{5}) +(?<bid>[0-9]+)$/', trim($line), $matches)) {
        $sets[] = [
            'hand' => $matches['hand'],
            'bid'  => intval($matches['bid']),
            'strength' => [
                getStrengthOfHand($matches['hand']),
                getStrengthOfCard($matches['hand'][0]),
                getStrengthOfCard($matches['hand'][1]),
                getStrengthOfCard($matches['hand'][2]),
                getStrengthOfCard($matches['hand'][3]),
                getStrengthOfCard($matches['hand'][4]),
            ],
        ];
    }
}
fclose($file);

print("Sorting list\n");

usort($sets, static function (array $set1, array $set2): int
{
    for ($i = 0; $i < 6; ++$i)
    {
        if ($set1['strength'][$i] > $set2['strength'][$i])
            return 1;
        if ($set1['strength'][$i] < $set2['strength'][$i])
            return -1;
    }
    return 0;
});


print("Calculating Scores\n");
$sum = 0;
$length = count($sets);

for($i = 0; $i < $length; ++$i)
{
    $hand = $sets[$i]['hand'];
    $score = ($i + 1) * $sets[$i]['bid'];
    $sum += $score;
    print("$i:\t $hand\t - $score\t - $sum\n");
}



function getStrengthOfHand(string $cards): int
{
    $amountPerCard = getAmount($cards);
    $cardsPerAmount = [];
    foreach ($amountPerCard as $card => $amount) {
        if (!isset($cardsPerAmount[$amount])) {
            $cardsPerAmount[$amount] = [];
        }
        $cardsPerAmount[$amount][] = "$card";
    }
    // Five of a kind
    if (isset($cardsPerAmount[5]))
        return 7;
    // Four of a kind
    if (isset($cardsPerAmount[4]))
        return 6;
    // Full house
    if (isset($cardsPerAmount[3]) && isset($cardsPerAmount[2]))
        return 5;
    // Three of a kind
    if (isset($cardsPerAmount[3]))
        return 4;
    // Two pair
    if (isset($cardsPerAmount[2]) && count($cardsPerAmount[2]) === 2)
        return 3;
    // One pair
    if (isset($cardsPerAmount[2]))
        return 2;
    // High card
    if (isset($cardsPerAmount[1]) && count($cardsPerAmount[1]) === 5)
        return 1;
    return 0;
}

function getAmount(string $cards): array
{
    $result = [];
    $length = strlen($cards);
    for ($i = 0; $i < $length; ++$i) {
        $result[$cards[$i]] = 1 + ($result[$cards[$i]] ?? 0);
    }
    return $result;
}

function getStrengthOfCard(string $card): int {
    switch (strtoupper(substr($card, 0, 1)))
    {
        case '1':
        case '2':
        case '3':
        case '4':
        case '5':
        case '6':
        case '7':
        case '8':
        case '9':
            return intval($card);
        case 'T':
            return 10;
        case 'J':
            return 11;
        case 'Q':
            return 12;
        case 'K':
            return 13;
        case 'A':
            return 14;
        default:
            return 0;
    }
}
