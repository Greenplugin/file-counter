<?php
declare(strict_types=1);

interface CounterInterface
{
    public function increment(): int;

    public function get(): int;
}