<?php
require __DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'include.php';

$parsed = parseFile();


$state = new WorkflowState(...$parsed->workflows);

$sum = 0;
foreach ($parsed->inputs as $input) {
    if ($state->execute($input))
        $sum += $input->getSum();
}
print("Sum: $sum\n");
