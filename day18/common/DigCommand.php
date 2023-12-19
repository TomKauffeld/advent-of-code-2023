<?php

const DIRECTION_UP = 3;
const DIRECTION_LEFT = 2;
const DIRECTION_RIGHT = 0;
const DIRECTION_DOWN = 1;

class DigCommand
{
    private int $direction;
    private int $length;
    private string $data;

    public function __construct(int $direction, int $length, string $data)
    {
        $this->direction = $direction;
        $this->length = $length;
        $this->data = $data;
    }

    public function getDirection(): int
    {
        return $this->direction;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function getDataDirection(): int
    {
        $data = $this->getData();
        return intval($data[strlen($data) - 1]);
    }

    public function getDataLength(): int
    {
        $data = $this->getData();
        $data = substr($data, 0, strlen($data) - 1);
        return intval($data, 16);
    }
}
