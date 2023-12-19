<?php
require __DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'include.php';

$parsed = parseFile();

const MIN = 1;
const MAX = 4000;

$state = new WorkflowState(...$parsed->workflows);

$range = new InputRange();
$range->setRange('x', new NumberRange(MIN, MAX));
$range->setRange('m', new NumberRange(MIN, MAX));
$range->setRange('a', new NumberRange(MIN, MAX));
$range->setRange('s', new NumberRange(MIN, MAX));
$test = $state->compile($range);

$sum = 0;
foreach ($test as $item)
{
    if ($item->getResult()->isAccepted() === true) {
        $combinations = $item->getCombinations();
        $sum += $combinations;
        print("$combinations\t - $sum\n");
    }
}
