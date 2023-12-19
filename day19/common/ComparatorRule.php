<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

class ComparatorRule extends WorkflowRule
{
    private string $part;
    private string $operator;
    private int $value;


    public function __construct(WorkflowResult $result, string $part, string $operator, int $value)
    {
        parent::__construct($result);
        $this->part = $part;
        $this->operator = $operator;
        $this->value = $value;
    }


    public function execute(InputSet $input): ?WorkflowResult
    {
        $value = $input->getValue($this->getPartName());
        if ($value === null)
            throw new RuntimeException('Part not found');
        switch ($this->getOperator())
        {
            case '>':
                return $value > $this->getValue() ? $this->getResult() : null;
            case '<':
                return $value < $this->getValue() ? $this->getResult() : null;
            default:
                throw new RuntimeException('Invalid operator: ' . $this->getOperator());
        }
    }

    public function getPartName(): string
    {
        return $this->part;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public static function Parse(string $text): ?self
    {
        if (preg_match('/^(?<p>[a-z]+)(?<o>[><])(?<v>\\d+):(?<r>([a-z]+)|[RA])$/', $text, $matches))
        {
            return new self(
                WorkflowResult::Parse($matches['r']),
                $matches['p'],
                $matches['o'],
                intval($matches['v']),
            );
        }
        return null;
    }

    public function compile(InputRange $range): ?InputRangeResult
    {
        $r = $range->getRange($this->getPartName());
        switch ($this->getOperator())
        {
            case '>':
                $used = $r->getWithMin($this->getValue() + 1);
                $ignored = $r->getWithMax($this->getValue());
                break;
            case '<':
                $used = $r->getWithMax($this->getValue() - 1);
                $ignored = $r->getWithMin($this->getValue());
                break;
            default:
                throw new RuntimeException('Invalid operator: ' . $this->getOperator());
        }

        if (!$used->isValid())
            return null;

        if (!$ignored->isValid())
            $nextRange = new InputRange();
        else
        {
            $nextRange = new InputRange($range);
            $nextRange->setRange($this->getPartName(), $ignored);
        }

        $result = new InputRangeResult($this->getResult(), $range, $nextRange);
        $result->setRange($this->getPartName(), $used);
        return $result;
    }
}
