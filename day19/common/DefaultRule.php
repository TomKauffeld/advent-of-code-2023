<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

class DefaultRule extends WorkflowRule
{
    public function __construct(WorkflowResult $result)
    {
        parent::__construct($result);
    }


    public function execute(InputSet $input): ?WorkflowResult
    {
        return $this->getResult();
    }

    public static function Parse(string $text): ?self
    {
        if (preg_match('/^(?<r>([a-z]+)|[RA])$/', $text, $matches))
        {
            return new self(
                WorkflowResult::Parse($matches['r']),
            );
        }
        return null;
    }

    public function compile(InputRange $range): InputRangeResult
    {
        return new InputRangeResult($this->getResult(), $range, new InputRange());
    }
}
