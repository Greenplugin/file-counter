<?php
declare(strict_types=1);

class SimpleCounter extends CounterPathResolver implements CounterInterface
{

    /**
     * @param int $index
     * @return int
     */
    public function increment($index = 1): int
    {
        return $this->incrementFile();
    }

    /**
     * @return int
     */
    private function incrementFile(): int
    {
        $index = 0;

        $fp = $this->openAndLockFile($this->getPartialName($index));

        while (!$fp) {
            $fp = $this->openAndLockFile($this->getPartialName($index));
            $index++;
        }

        $counter = $this->readIntFromFile($fp);
        $counter++;

        $this->rewriteFile($fp, (string)$counter);


        return $this->get() + $counter;
    }

    /**
     * @return int
     */
    public function get(): int
    {
        return (int)file_get_contents($this->getMainCounterName());
    }
}