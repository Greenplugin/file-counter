<?php
declare(strict_types=1);

namespace Counter;

class RedisFileCounter implements CounterInterface
{
    /**
     * @var string
     */
    private $counterName;

    /**
     * @var \Redis
     */
    private $redis;

    /**
     * RedisFileCounter constructor.
     * @param \Redis $redisInstance
     * @param string $counterName
     */
    public function __construct(\Redis $redisInstance, string $counterName)
    {
        $this->redis = $redisInstance;
        $this->counterName = $counterName;
    }

    /**
     * @return int
     */
    public function increment(): int
    {
        $this->redis->incr($this->counterName);
        return intval($this->get());
    }

    /**
     * @return int
     */
    public function get(): int
    {
        return intval($this->redis->get($this->counterName));
    }
}