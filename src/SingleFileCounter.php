<?php
declare(strict_types=1);

namespace Counter;


class SingleFileCounter implements CounterInterface
{

    /**
     * @var string
     */
    private $path;

    /**
     * CounterPathResolver constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        if (!is_dir($path)) {
            mkdir($path, 0755);
        }
    }

    public function increment(): int
    {
        $fp = fopen($this->getCounterName(), "c+");

        flock($fp, LOCK_EX);
        $count = intval(fread($fp, strlen(strval(PHP_INT_MAX))));
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, strval($count + 1));
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
        return intval($count);
    }

    public function get(): int
    {
        if (is_file($this->getCounterName())) {
            return (int)file_get_contents($this->getCounterName());
        }

        return 0;
    }

    /**
     * @return string
     */
    private function getCounterName(): string
    {
        return $this->path . DIRECTORY_SEPARATOR . "counter";
    }
}