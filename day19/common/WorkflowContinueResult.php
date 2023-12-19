<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

class WorkflowContinueResult extends WorkflowResult
{
    private string $nextWorkflow;

    public function __construct(string $nextWorkflow)
    {
        $this->nextWorkflow = $nextWorkflow;
    }

    public function getNextWorkflow(): string
    {
        return $this->nextWorkflow;
    }

    public function isAccepted(): ?bool
    {
        return null;
    }
}
