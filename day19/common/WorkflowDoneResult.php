<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

class WorkflowDoneResult extends WorkflowResult
{
    private bool $accepted;

    public function __construct(bool $accepted)
    {
        $this->accepted = $accepted;
    }

    public function getNextWorkflow(): ?string
    {
        return null;
    }

    public function isAccepted(): bool
    {
        return $this->accepted;
    }
}
