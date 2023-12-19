<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'include.php';


/**
 * @param bool $test
 * @return DigCommand[]
 */
function getCommands(bool $test = false): array
{
    $file = getInputFile($test);
    $commands = [];

    while (($line = fgets($file)) !== false)
    {
        $line = trim($line);
        $command = parseLine($line);
        if ($command !== null)
            $commands[] = $command;
    }


    fclose($file);
    return $commands;
}


function parseLine(string $line): ?DigCommand
{
    $directionMap = [
        'U' => DIRECTION_UP,
        'R' => DIRECTION_RIGHT,
        'D' => DIRECTION_DOWN,
        'L' => DIRECTION_LEFT,
    ];
    $directionPattern = '(?<direction>[RLUD])';
    $lengthPattern = '(?<length>\\d+)';
    $colors = "\\(#(?<color>[\\da-f]{6})\\)";
    $pattern = "/^$directionPattern $lengthPattern $colors$/";
    if (preg_match($pattern, $line, $matches))
    {
        return new DigCommand(
            $directionMap[$matches['direction']],
            intval($matches['length']),
            $matches['color']
        );
    }
    return null;
}

function createPolygon(DigCommand ... $commands): Polygon
{
    $currentX = 0;
    $currentY = 0;
    $polygon = new Polygon();
    $polygon->addVertex(0, 0);
    foreach ($commands as $command)
    {
        $direction = $command->getDataDirection();
        $length = $command->getDataLength();
        switch ($direction)
        {
            case DIRECTION_UP:
                $currentY -= $length;
                break;
            case DIRECTION_DOWN:
                $currentY += $length;
                break;
            case DIRECTION_LEFT:
                $currentX -= $length;
                break;
            case DIRECTION_RIGHT:
                $currentX += $length;
                break;
        }
        $polygon->addVertex($currentX, $currentY);
    }
    return $polygon;
}


function runCommands(DigMap $map, int &$currentX, int &$currentY, DigCommand ... $commands): void
{
    foreach ($commands as $command)
    {
        for($i = 0; $i < $command->getLength(); ++$i)
        {
            switch ($command->getDirection())
            {
                case DIRECTION_UP:
                    --$currentY;
                    break;
                case DIRECTION_DOWN:
                    ++$currentY;
                    break;
                case DIRECTION_LEFT:
                    --$currentX;
                    break;
                case DIRECTION_RIGHT:
                    ++$currentX;
                    break;
            }
            $map->set($currentX, $currentY, $command);
        }
    }
}
