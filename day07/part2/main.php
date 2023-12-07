<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

$file = getInputFile(7);

$sets = [];

print("Reading file\n");

while (($line = fgets($file)) !== false)
{
    if (preg_match('/^(?<hand>[123456789TJQKA]{5}) +(?<bid>[0-9]+)$/', trim($line), $matches)) {
        $set = [
            'hand' => $matches['hand'],
            'bid'  => intval($matches['bid']),
            'strength' => getStrengthsOfCards($matches['hand']),
        ];
        print($set['hand']."\n");

        $sets[] = $set;
    }
}
fclose($file);

print("Sorting list\n");

usort($sets, static function (array $set1, array $set2): int
{
    return getRelativeStrength($set1['strength'], $set2['strength']);
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



function getRelativeStrength(array $s1, array $s2): int
{
    $amount = max(count($s1), count($s2));
    for ($i = 0; $i < $amount; ++$i) {
        $score1 = $s1[$i] ?? -1;
        $score2 = $s2[$i] ?? -1;
        if ($score1 > $score2)
            return 1;
        if ($score1 < $score2)
            return -1;
    }
    return 0;
}

function getMaxStrengthOfHand(string $cards): int
{
    $index = stripos($cards, 'J');
    if ($index === false)
        return getStrengthOfHand($cards);
    $replaced = '123456789TQKA';
    $length = strlen($replaced);
    $maxScore = null;
    for($i = 0; $i < $length; ++$i)
    {
        $cards[$index] = $replaced[$i];
        $score = getMaxStrengthOfHand($cards);
        if ($maxScore === null || $score > $maxScore)
            $maxScore = $score;
    }
    return $maxScore;
}

function getStrengthsOfCards(string $cards): array
{
    return [
        getMaxStrengthOfHand($cards),
        getStrengthOfCard($cards[0]),
        getStrengthOfCard($cards[1]),
        getStrengthOfCard($cards[2]),
        getStrengthOfCard($cards[3]),
        getStrengthOfCard($cards[4]),
    ];
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
        case 'Q':
            return 12;
        case 'K':
            return 13;
        case 'A':
            return 14;
        case 'J':
        default:
            return 0;
    }
}
