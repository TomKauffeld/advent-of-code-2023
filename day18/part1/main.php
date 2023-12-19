<?php
require_once __DIR__ .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'include.php';


$commands = getCommands();
$map = new DigMap();

$currentX = 0;
$currentY = 0;
$map->set($currentX, $currentY, $commands);

runCommands($map, $currentX, $currentY, ...$commands);

$map->display();
print ("\n");

fillInside($map);

$map->display();

$filled = $map->getDataSize();
print("Filled: $filled\n");
