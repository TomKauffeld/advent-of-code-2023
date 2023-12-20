<?php
namespace Aoc\Y2023\Day20\Common;

require __DIR__ . DIRECTORY_SEPARATOR . 'include.php';

function parseFile(bool $test = false): Machine
{
    $file = getInputFile($test);
    $machine = new Machine();

    while (($line = fgets($file)) !== false)
    {
        $line = trim($line);
        if (empty($line)) {
            continue;
        }
        if (preg_match('/^%(?<source>[a-z]+) +-> +(?<targets>[a-z]+(?:, *[a-z]+)*)$/', $line, $matches)) {
            $module = new FlipFlopModule($matches['source'], ...targetsToList($matches['targets']));
        } elseif (preg_match('/^&(?<source>[a-z]+) +-> +(?<targets>[a-z]+(?:, *[a-z]+)*)$/', $line, $matches)) {
            $module = new ConjunctionModule($matches['source'], ...targetsToList($matches['targets']));
        } elseif (preg_match('/^(?<source>broadcaster) +-> +(?<targets>[a-z]+(?:, *[a-z]+)*)$/', $line, $matches)) {
            $module = new BroadcastModule($matches['source'], ...targetsToList($matches['targets']));
        } else {
            $module = null;
            print("INVALID FORMAT: $line\n");
        }
        if ($module !== null)
            $machine->addModule($module);

    }
    fclose($file);

    return $machine;
}


function targetsToList(string $targets): array
{
    return array_filter(array_map(static function (string $value): string {
        return trim($value);
    }, explode(',', $targets)), static function (string $value): bool {
        return !empty($value);
    });
}
