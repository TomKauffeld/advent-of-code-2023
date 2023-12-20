<?php

namespace Aoc\Y2023\Day20\Common;

class Pulse
{
    private string $source;
    private string $target;
    public bool $highPulse;

    public function __construct(string $source, string $target, bool $isHighPulse)
    {
        $this->source = $source;
        $this->target = $target;
        $this->highPulse = $isHighPulse;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function isHighPulse(): bool
    {
        return $this->highPulse;
    }

    public function isLowPulse(): bool
    {
        return !$this->isHighPulse();
    }


    public static function LowPulse(string $source, string $target): self
    {
        return new self($source, $target, false);
    }

    public static function HighPulse(string $source, string $target): self
    {
        return new self($source, $target, true);
    }

    public static function NewPulse(string $source, string $target, Pulse $from): self
    {
        return new self($source, $target, $from->isHighPulse());
    }
}
