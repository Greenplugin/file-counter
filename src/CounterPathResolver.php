<?php
declare(strict_types=1);

namespace Counter;

abstract class CounterPathResolver
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
        if (!is_dir($this->getPartialsDir())) {
            mkdir($this->getPartialsDir(), 0755);
        }
    }

    /**
     * @param int $index
     * @return string
     */
    protected function getPartialName(int $index): string
    {
        return sprintf("%s_part_%s", $this->getPartialsDir() . DIRECTORY_SEPARATOR, $index);
    }

    /**
     * @return string
     */
    protected function getMainCounterPath(): string
    {
        return $this->path . DIRECTORY_SEPARATOR . "counter";
    }

    /**
     * @return array
     */
    protected function getAllPartials(): array
    {
        $result = [];

        foreach (scandir($this->getPartialsDir()) as $file) {
            if (!is_dir($this->getPartialsDir() . DIRECTORY_SEPARATOR . $file)) {
                $result[] = $this->getPartialsDir() . DIRECTORY_SEPARATOR . $file;
            }
        }

        return $result;
    }

    /**
     * @param string $path
     * @return bool|resource
     */
    protected function openAndLockFile(string $path)
    {
        $fp = fopen($path, "c+");

        if (!$fp || !flock($fp, LOCK_EX | LOCK_NB, $eWouldBlock) || $eWouldBlock) {
            return false;
        }

        return $fp;
    }

    /**
     * @param $fp
     * @param $path
     * @return int
     */
    protected function readIntFromFile($fp): int
    {
        return (int)fread($fp, strlen(strval(PHP_INT_MAX)));
    }

    /**
     * @param $fp
     * @param string $contents
     */
    protected function rewriteFile($fp, string $contents)
    {
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, $contents);
    }

    /**
     * @param $fp
     */
    protected function closeAndUnlockFile($fp)
    {
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /**
     * @return string
     */
    private
    function getPartialsDir(): string
    {
        return $this->path . DIRECTORY_SEPARATOR . "partials";
    }
}