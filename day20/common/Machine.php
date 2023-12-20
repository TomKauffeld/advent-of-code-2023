<?php

namespace Aoc\Y2023\Day20\Common;

class Machine
{
    private bool $modulesReady = false;
    private array $modules = [];
    private array $t2s = [];
    private string $startModuleName;
    private bool $highPulse;

    public function __construct(string $startModule = 'broadcaster', bool $isHighPulse = false)
    {
        $this->startModuleName = $startModule;
        $this->highPulse = $isHighPulse;
    }

    public function addModule(Module $module): void
    {
        $this->modulesReady = false;
        $this->modules[$module->getName()] = $module;
        foreach ($module->getConnected() as $target)
        {
            if (!isset($this->t2s[$target]))
                $this->t2s[$target] = [];
            if (!in_array($module->getName(), $this->t2s[$target]))
                $this->t2s[$target][] = $module->getName();
        }
    }

    public function getModule(string $name): ?Module
    {
        if (!$this->modulesReady)
            $this->prepareModules();
        return $this->modules[$name] ?? null;
    }

    public function getStartModuleName(): string
    {
        return $this->startModuleName;
    }

    public function isStartHighPulse(): bool
    {
        return $this->highPulse;
    }

    public function getStartPulse(): Pulse
    {
        return new Pulse('button', $this->getStartModuleName(), $this->isStartHighPulse());
    }

    protected function prepareModules(): void
    {
        $this->modulesReady = true;
        foreach ($this->t2s as $target => $sources)
        {
            $module = $this->getModule($target);
            if ($module instanceof KnowsSources)
                $module->setSources(...$sources);
        }
    }

    public function getModuleNamesWithTarget(string $target): array
    {
        return $this->t2s[$target] ?? [];
    }


    /**
     * @return Pulse[]
     */
    public function execute(): array
    {
        $this->prepareModules();
        $todo = [$this->getStartPulse()];
        $done = [];
        $pc = 0;

        while (($pulse = array_shift($todo)) !== null)
        {
            $done[] = $pulse;
            $module = $this->getModule($pulse->getTarget());
            if ($module === null)
                continue;
            $pulses = $module->execute($pulse, $pc++);
            array_push($todo, ...$pulses);
        }

        return $done;
    }

    /**
     * @return Pulse[]
     */
    public function executeWithCallback(string $target, ExecuteCallback $callback): array
    {
        $todo = [$this->getStartPulse()];
        $found = [];
        $pc = 0;

        while (($pulse = array_shift($todo)) !== null)
        {
            if ($pulse->getTarget() === $target)
                $found[] = $pulse;
            $module = $this->getModule($pulse->getTarget());
            if ($module === null)
                continue;
            $pulses = $module->execute($pulse, $pc);
            $callback->execute($this, $pulse, $pc++);
            array_push($todo, ...$pulses);
        }

        return $found;
    }
}
