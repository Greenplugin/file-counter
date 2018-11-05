<?php
declare(strict_types=1);

namespace Counter;

class SimpleCounter extends CounterPathResolver implements CounterInterface
{

    /**
     * @param int $index
     * @return int
     */
    public function increment(): int
    {
        $index = 0;

        $fp = $this->openAndLockFile($this->getPartialName($index));

        while (!$fp) {
            $fp = $this->openAndLockFile($this->getPartialName($index));
            $index++;
        }

        $counter = $this->readIntFromFile($fp);
        $counter++;

        $this->rewriteFile($fp, strval($counter));

        $this->closeAndUnlockFile($fp);

        return $this->get() + $counter;
    }

    /**
     * @return int
     */
    public function get(): int
    {
        if (is_file($this->getMainCounterPath())) {
            return (int)file_get_contents($this->getMainCounterPath());
        }

        return 0;
    }
}