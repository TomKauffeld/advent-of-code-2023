<?php

namespace Aoc\Y2023\Day20\Common;

interface KnowsSources
{
    public function setSources(string ...$sources): void;
    /** @return string[] */
    public function getSources(): array;
}
