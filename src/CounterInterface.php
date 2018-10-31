<?php
declare(strict_types=1);

namespace Counter;

interface CounterInterface
{
    public function increment(): int;

    public function get(): int;
}