<?php
namespace Aoc\Y2023\Day20\Common;

use RuntimeException;

require __DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'include.php';

const LOOP_LIMIT = 100_000;
const TARGET = 'rx';

$machine = parseFile();

$last = $machine->getModuleNamesWithTarget(TARGET);

if (count($last) !== 1)
    throw new RuntimeException('Expected only 1 with "' . TARGET . '" target');

$lastModule = $machine->getModule($last[0]);

if (!($lastModule instanceof ConjunctionModule))
    throw new RuntimeException("Expected last \"$last[0]\" to be a ConjunctionModule");

$entries = $machine->getModuleNamesWithTarget($last[0]);
global $cycles, $foundCycles, $bc;
$cycles = [];

foreach ($entries as $entry)
{
    $module = $machine->getModule($entry);
    if (!($module instanceof ConjunctionModule))
        throw new RuntimeException("Expected \"$entry\" to be a ConjunctionModule");
    $cycles[$entry] = ['start' => null, 'loop' => null, 'again' => null];
}

$foundCycles = false;
$bc = 0;
while(!$foundCycles)
{
    if ($bc > LOOP_LIMIT)
        throw new RuntimeException("No loops found after $bc presses...");
    $foundCycles = true;
    $machine->executeWithCallback(TARGET, new class implements ExecuteCallback {
        public function execute(Machine $machine, Pulse $pulse, int $pc): void
        {
            global $cycles, $foundCycles, $bc;
            if (!isset($cycles[$pulse->getTarget()]))
                return;
            $moduleName = $pulse->getTarget();
            $module = $machine->getModule($moduleName);

            if (!($module instanceof ConjunctionModule))
                throw new RuntimeException("Expected \"$moduleName\" to be a ConjunctionModule");

            if ($cycles[$moduleName]['start'] === null && !$module->isAllHighPulses())
                $cycles[$moduleName]['start'] = ['bc' => $bc, 'pc' => $pc];
            elseif ($cycles[$moduleName]['loop'] === null && !$module->isAllHighPulses())
                $cycles[$moduleName]['loop'] = ['bc' => $bc, 'pc' => $pc];
            elseif ($cycles[$moduleName]['loop'] !== null && $cycles[$moduleName]['again'] === null && $module->isAllHighPulses())
                $cycles[$moduleName]['again'] = ['bc' => $bc, 'pc' => $pc];
            $foundCycles &= $cycles[$moduleName]['loop'] !== null;
        }
    });
    ++$bc;
}

$firstAgain = null;
$lastPC = null;
$cyclePresses = [];
foreach ($cycles as $cycle)
{
    if ($firstAgain === null || $firstAgain > $cycle['again']['pc'])
        $firstAgain = $cycle['again']['pc'];
    if ($lastPC === null || $lastPC < $cycle['loop']['pc'])
        $lastPC = $cycle['loop']['pc'];
    $cyclePresses[] = $cycle['loop']['bc'] - $cycle['start']['bc'];
}

if ($lastPC > $firstAgain)
    throw new RuntimeException('Cannot find solution');

function gcd(int $a, int $b): int
{
    if ($a === $b || $b === 0 || $a === 0)
        return max($a, $b);
    if ($a < $b)
        return gcd($a, $b % $a);
    return gcd($b, $a % $b);
}

function lcm(int $a, int $b): int
{
    $gcd = gcd($a, $b);
    if ($gcd === 0)
        return 0;
    return $a * ($b / $gcd);
}


while (count($cyclePresses) > 1) {
    $a = array_shift($cyclePresses);
    $b = array_shift($cyclePresses);
    $cyclePresses[] = lcm($a, $b);
}

$cycle = $cyclePresses[0];

print("Loop length: $cycle\n");
