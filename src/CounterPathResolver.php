<?php
declare(strict_types=1);

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
    protected function getMainCounterName(): string
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
     * @param $path
     * @return bool|resource
     */
    protected function openAndLockFile(string $path)
    {
        $fp = fopen($path, "r+");

        if (!$fp || !flock($fp, LOCK_EX | LOCK_NB, $eWouldBlock) || $eWouldBlock) {
            return false;
        }

        return $fp;
    }

    /**
     * @param $fp
     * @return int
     */
    protected function readIntFromFile($fp): int
    {
        return (int)fread($fp, fstat($fp)['size']);
    }

    /**
     * @param $fp
     * @param string $contents
     */
    protected function rewriteFile($fp, string $contents)
    {
        ftruncate($fp, 0);
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