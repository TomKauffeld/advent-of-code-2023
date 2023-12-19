<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

class Workflow
{
    private string $name;
    private array $rules;

    public function __construct(string $name, WorkflowRule ...$rules)
    {
        $this->name = $name;
        $this->rules = $rules;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function execute(InputSet $input): ?WorkflowResult
    {
        foreach ($this->rules as $rule)
        {
            $result = $rule->execute($input);
            if ($result !== null)
                return $result;
        }
        return null;
    }

    /**
     * @param InputRange $range
     * @return InputRangeResult[]
     */
    public function compile(InputRange $range): array
    {
        $results = [];
        foreach ($this->rules as $rule)
        {
            $result = $rule->compile($range);

            if ($result === null)
                continue;

            $range = $result->getIgnored();
            $results[] = $result;
        }
        return $results;
    }


    public static function Parse(string $text): self
    {
        $sText = strlen($text);

        $f = strpos($text, '{');
        $l = $text[$sText - 1] === '}' ? $sText - 1 : false;

        if ($f === false || $l === false)
            throw new RuntimeException('Invalid line');

        $stringRules = substr($text, $f + 1, $l - $f - 1);

        $name = substr($text, 0, $f);

        $rules = array_map(static function (string $part): WorkflowRule {
            return WorkflowRule::Parse($part);
        }, explode(',', $stringRules));

        return new self($name, ...$rules);
    }
}
