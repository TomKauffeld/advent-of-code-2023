<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

abstract class WorkflowResult
{

    public abstract function getNextWorkflow(): ?string;

    public abstract function isAccepted(): ?bool;


    public static function Parse(string $text): self
    {
        switch ($text)
        {
            case 'A':
                return new WorkflowDoneResult(true);
            case 'R':
                return new WorkflowDoneResult(false);
            default:
                return new WorkflowContinueResult($text);
        }
    }
}
