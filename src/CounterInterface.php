<?php
declare(strict_types=1);

namespace Counter;

interface CounterInterface
{
    /**
     * @return int
     */
    public function increment(): int;

    /**
     * @return int
     */
    public function get(): int;
}