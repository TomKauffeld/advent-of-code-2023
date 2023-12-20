<?php

namespace Aoc\Y2023\Day20\Common;

interface ExecuteCallback
{
    public function execute(Machine $machine, Pulse $pulse, int $pc): void;
}
