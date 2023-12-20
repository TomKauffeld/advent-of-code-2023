<?php
namespace Aoc\Y2023\Day20\Common;

require __DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'include.php';

const PULSES = 1000;

$machine = parseFile();

$high = 0;
$low = 0;
for ($i = 0; $i < PULSES; $i++) {
    $pulses = $machine->execute();
    $localHigh = 0;
    $localLow = 0;
    foreach ($pulses as $pulse)
    {
        if ($pulse->isHighPulse())
            ++$localHigh;
        if ($pulse->isLowPulse())
            ++$localLow;
    }
    $low += $localLow;
    $high += $localHigh;
    print("Button $i:\t $localLow  \t $localHigh \t - $low\t - $high\n");
}

$total = $low + $high;
$mul = $low * $high;
print("LOW:        $low\n");
print("HIGH:       $high\n");
print("TOTAL:      $total\n");
print("MULTIPLIED: $mul\n");
