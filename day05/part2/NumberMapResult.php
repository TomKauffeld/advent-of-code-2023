<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'NumberRange.php';

class NumberMapResult
{
    private NumberRange $done;
    /** @var NumberRange[] */
    private array $ignored;

    public function __construct(NumberRange $done, NumberRange... $ignored)
    {
        $this->done = $done;
        $this->ignored = $ignored;
    }

    public function getDone(): NumberRange
    {
        return $this->done;
    }

    /** @return NumberRange[] */
    public function getIgnored(): array
    {
        return $this->ignored;
    }
}
