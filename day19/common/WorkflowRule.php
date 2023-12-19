<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

abstract class WorkflowRule
{

    private WorkflowResult $result;


    public abstract function execute(InputSet $input): ?WorkflowResult;

    public abstract function compile(InputRange $range): ?InputRangeResult;

    public function __construct(WorkflowResult $result)
    {
        $this->result = $result;
    }

    public function getResult(): WorkflowResult
    {
        return $this->result;
    }


    public static function Parse(string $text): ?self
    {
        return ComparatorRule::Parse($text) ?? DefaultRule::Parse($text);
    }
}
