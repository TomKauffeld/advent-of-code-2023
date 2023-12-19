<?php

require_once __DIR__ . DIRECTORY_SEPARATOR . 'include.php';


function hasDownConnection(DigMap $map, int $x, int $y): bool
{
    $value = $map->getValue($x, $y);
    if ($value instanceof DigCommand)
    {
        if ($value->getDirection() === DIRECTION_UP)
            return true;
        if ($y < $map->getMaxY())
        {
            $value = $map->getValue($x, $y + 1);
            if ($value instanceof DigCommand)
                return $value->getDirection() === DIRECTION_DOWN;
            if (is_array($value))
                return true;
        }
    }
    if (is_array($value))
        return true;
    return false;
}

function fillInside(DigMap $map): void
{
    for ($y = $map->getMinY(); $y <= $map->getMaxY(); ++$y)
    {
        $fill = false;
        for ($x = $map->getMinX(); $x <= $map->getMaxX(); ++$x)
        {
            $value = $map->getValue($x, $y);
            if ($value !== null && hasDownConnection($map, $x, $y)) {
                $fill = !$fill;
            } elseif ($value === null && $fill) {
                $map->set($x, $y, true);
            }
        }
    }
}
