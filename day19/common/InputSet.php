<?php

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

class InputSet
{
    private array $values = [];

    public function setValue(string $name, int $value): void
    {
        $this->values[$name] = $value;
    }

    public function getValue(string $name): ?int
    {
        return $this->values[$name];
    }

    public function getSum(): int
    {
        return array_reduce($this->values, static function(int $a, int $c): int {
            return $a + $c;
        }, 0);
    }


    public static function Parse(string $line): self
    {
        $sLine = strlen($line);
        if ($line[0] !== '{' || $line[$sLine - 1] !== '}')
            throw new RuntimeException('Invalid format');
        $line = substr($line, 1, $sLine - 2);
        $set = new self();
        $parts = explode(',', $line);
        foreach ($parts as $part)
        {
            $index = strpos($part, '=');
            if ($index === false)
                throw new RuntimeException('Invalid format');
            $name = substr($part, 0, $index);
            $value = substr($part, $index + 1);
            $set->setValue($name, intval($value));
        }
        return $set;
    }
}
