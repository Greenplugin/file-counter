<?php
declare(strict_types=1);

namespace Counter;


class SimpleCounterReaper extends CounterPathResolver
{

    public function reap()
    {
        $partials = $this->getAllPartials();

        $counter = 0;

        foreach ($partials as $partial) {
            $fp = $this->openAndLockFile($partial);
            $counter += $this->readIntFromFile($fp);
            $this->rewriteFile($fp, '0');
            $this->closeAndUnlockFile($fp);
        }

        if (file_exists($this->getMainCounterPath())) {
            $counter += file_get_contents($this->getMainCounterPath());
        }
        file_put_contents($this->getMainCounterPath(), $counter);

        return $counter;
    }
}