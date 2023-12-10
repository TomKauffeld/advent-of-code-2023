<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'include.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'readData.php';

const TEST = false;


$cycle = getCycle(TEST);
if (TEST) {
    $file = fopen('test.txt', 'r');
} else {
    $file = getInputFile(10);
}
$maxY = 0;
$maxX = 0;
while (($line = fgets($file)) !== false) {
    $len = strlen(trim($line));
    $maxX = max($maxX, $len);
    ++$maxY;
}
fseek($file, 0);


$image = imagecreatetruecolor($maxX * 3, $maxY * 3);
$output = fopen('result.txt', 'w');

$wall = imagecolorallocate($image, 255, 0, 0);
$space = imagecolorallocate($image, 0, 0, 255);
$empty = imagecolorallocate($image, 0, 255, 0);

$y = 0;
$sum = 0;
$insideTop = [];
while (($line = fgets($file)) !== false)
{
    $line = trim($line);
    $len = strlen($line);
    $inside = false;
    $row = 0;
    for ($x = 0; $x < $len; ++$x)
    {
        $node = $cycle->getNodeAt($x, $y);
        if ($node !== null && (FileParser::hasConnectionBottom($node, false))) {
            $inside = !$inside;
        } elseif ($node !== null && $node->data['char'] === 'S') {
            $bottom = $cycle->getNodeAt($x, $y + 1);
            if (FileParser::hasConnectionTop($bottom))
                $inside = !$inside;
        }
        if ($node !== null) {
            $char = $node->data['char'];
            if ($char === 'S') {
                $r = FileParser::getConnectionRight($cycle, $node) !== null;
                $l = FileParser::getConnectionLeft($cycle, $node) !== null;
                $t = FileParser::getConnectionTop($cycle, $node) !== null;
                $b = FileParser::getConnectionBottom($cycle, $node) !== null;
                if ($r && $l) {
                    $char = '-';
                }elseif ($t && $b) {
                    $char = '|';
                }elseif($r && $t) {
                    $char = 'L';
                }elseif($r && $b) {
                    $char = 'F';
                }elseif($l && $t) {
                    $char = 'J';
                }elseif($l && $b) {
                    $char = '7';
                }
            }

            switch ($char) {
                case 'L':
                    drawWallVertical($image, $wall, $x, $y, true);
                    drawWallHorizontal($image, $wall, $x, $y, false);
                    imagesetpixel($image, $x * 3 + 2, $y * 3, $wall);
                    break;
                case 'J':
                    drawWallVertical($image, $wall, $x, $y, false);
                    drawWallHorizontal($image, $wall, $x, $y, false);
                    imagesetpixel($image, $x * 3, $y * 3, $wall);
                    break;
                case '7':
                    drawWallVertical($image, $wall, $x, $y, false);
                    drawWallHorizontal($image, $wall, $x, $y, true);
                    imagesetpixel($image, $x * 3, $y * 3 + 2, $wall);
                    break;
                case 'F':
                    drawWallVertical($image, $wall, $x, $y, true);
                    drawWallHorizontal($image, $wall, $x, $y, true);
                    imagesetpixel($image, $x * 3 + 2, $y * 3 + 2, $wall);
                    break;
                case '|':
                    drawWallVertical($image, $wall, $x, $y, true);
                    drawWallVertical($image, $wall, $x, $y, false);
                    break;
                case '-':
                    drawWallHorizontal($image, $wall, $x, $y, true);
                    drawWallHorizontal($image, $wall, $x, $y, false);
                    break;
            }
            fwrite($output, $node->data['char']);
        } elseif ($inside) {
            fill($image, $space, $x, $y);
            fwrite($output, '.');
            ++$row;
        } else {
            fill($image, $empty, $x, $y);
            fwrite($output, ' ');
        }

    }
    $sum += $row;

    print("$y\t - $row\t - $sum\n");
    fwrite($output, "\n");
    ++$y;
}
imagepng($image, 'result.png');
fclose($file);
fclose($output);



function drawWallVertical($image, $color, int $x, int $y, bool $left): void
{
    $offset = $left ? 0 : 2;
    for ($i = 0; $i < 3; ++$i)
        imagesetpixel($image, $x * 3 + $offset, $y * 3 + $i, $color);
}

function drawWallHorizontal($image, $color, int $x, int $y, bool $top): void
{
    $offset = $top ? 0 : 2;
    for ($i = 0; $i < 3; ++$i)
        imagesetpixel($image, $x * 3 + $i, $y * 3 + $offset, $color);
}

function fill($image, $color, int $x, int $y): void
{
    for ($dx = 0; $dx < 3; ++$dx)
        for ($dy = 0; $dy < 3; ++$dy)
            imagesetpixel($image, $x * 3 + $dx, $y * 3 + $dy, $color);
}
