<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

class WorkflowState
{
    private array $workflows = [];
    public function __construct(Workflow ...$workflows)
    {
        foreach ($workflows as $workflow)
            $this->workflows[$workflow->getName()] = $workflow;
    }

    public function getWorkflow(string $name): ?Workflow
    {
        return $this->workflows[$name] ?? null;
    }

    public function execute(InputSet $input): bool
    {
        $ip = 'in';
        $accepted = null;
        while($ip !== null && $accepted === null)
        {
            $workflow = $this->getWorkflow($ip);
            if ($workflow === null)
                throw new RuntimeException('Invalid workflow: ' . $ip);

            $result = $workflow->execute($input);

            $ip = $result->getNextWorkflow();
            $accepted = $result->isAccepted();
        }
        return $accepted;
    }

    /**
     * @param InputRange $range
     * @return InputRangeResult[]
     */
    public function compile(InputRange $range): array
    {
        $done = [];
        $stack = [new InputRangeResult(new WorkflowContinueResult('in'), $range, new InputRange())];
        while (($current = array_shift($stack)) !== null)
        {
            $ip = $current->getResult()->getNextWorkflow();
            $accepted = $current->getResult()->isAccepted();
            if ($accepted === true || $accepted === false) {
                $done[] = $current;
            }
            if ($ip !== null)
            {
                $workflow = $this->getWorkflow($ip);
                if ($workflow === null)
                    throw new RuntimeException('Invalid workflow: ' . $ip);

                $results = $workflow->compile($current);
                array_push($stack, ...$results);
            }
        }
        return $done;
    }
}
