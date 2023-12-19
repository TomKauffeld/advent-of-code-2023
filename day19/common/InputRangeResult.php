<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

class InputRangeResult extends InputRange
{
    private WorkflowResult $result;
    private InputRange $ignored;

    public function __construct(WorkflowResult $result, InputRange $range, InputRange $ignored)
    {
        parent::__construct($range);
        $this->result = $result;
        $this->ignored = $ignored;
    }

    public function getIgnored(): InputRange
    {
        return $this->ignored;
    }

    public function getResult(): WorkflowResult
    {
        return $this->result;
    }
}
