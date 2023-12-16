<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'utils.php';

const LIMIT = 255;


$boxes = readInputFile(false, false);

$focus = calculateFocusingPower($boxes);
print("Focus Power: $focus\n");


function calculateFocusingPower(array $boxes): int
{
    $sum = 0;
    foreach ($boxes as $i => $box)
    {
        foreach ($box as $j => $lens) {
            $sum += ($i + 1) * ($j + 1) * $lens['focal_length'];
        }
    }

    return $sum;
}


function readInputFile(bool $test = false, bool $debug = false): array
{
    $file = getInputFile($test);

    $boxes = array_fill(0, LIMIT + 1, []);
    $focalLengths = [];
    $buffer = "";
    while (($char = fgetc($file)) !== false)
    {
        switch ($char)
        {
            case ',':
                handleBuffer($buffer, $boxes, $focalLengths, $debug);
                $buffer = "";
                break;
            case "\r":
            case "\n":
                break;
            default:
                $buffer .= $char;
                break;
        }
    }
    fclose($file);
    if (strlen($buffer) > 0)
        handleBuffer($buffer, $boxes, $focalLengths, $debug);
    return array_map(static function (array $box) use ($focalLengths) : array {
        return array_map(static function (string $label) use ($focalLengths) : array {
            return ['label' => $label, 'focal_length' => $focalLengths[$label] ?? 0];
        }, $box);
    }, $boxes);
}

function printBoxes(array $boxes, array $focalLengths): void
{
    foreach ($boxes as $index => $box)
    {
        if (count($box) > 0) {
            $box_label = str_pad($index, 3, ' ', STR_PAD_LEFT);
            print ("Box $box_label: ");
            foreach ($box as $label)
                print("[$label " . $focalLengths[$label] . "] ");
            print ("\n");
        }
    }
}

function printState(string $buffer, array $boxes, array $focalLengths): void
{
    print("After \"$buffer\"\n");
    printBoxes($boxes, $focalLengths);
    print("\n");
}

function handleBuffer(string $buffer, array &$boxes, array &$focalLengths, bool $debug = false): void
{
    $sBuffer = strlen($buffer);
    if ($buffer[$sBuffer - 2] === '=')
    {
        $label = substr($buffer, 0, $sBuffer - 2);
        $box = holiday_hash($label);

        $focalLength = intval($buffer[$sBuffer - 1]);

        $focalLengths[$label] = $focalLength;
        if (!in_array($label, $boxes[$box]))
            $boxes[$box][] = $label;
    }
    elseif ($buffer[$sBuffer - 1] === '-')
    {
        $label = substr($buffer, 0, $sBuffer - 1);
        $box = holiday_hash($label);
        $index = array_search($label, $boxes[$box]);
        if ($index !== false)
            array_splice($boxes[$box], $index, 1);
    }
    else
    {
        throw new RuntimeException('invalid format');
    }
    if ($debug)
        printState($buffer, $boxes, $focalLengths);
}

function holiday_hash(string $input, int $initialValue = 0): int
{
    $hash = $initialValue % (LIMIT + 1);
    $sInput = strlen($input);
    for ($i = 0; $i < $sInput; ++$i)
    {
        $hash += ord($input[$i]);
        $hash *= 17;
        $hash %= (LIMIT + 1);
    }
    return $hash;
}
