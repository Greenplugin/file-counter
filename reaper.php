<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

$reaper = new \Counter\SimpleCounterReaper(__DIR__ . '/counter');

while (true) {
    var_dump($reaper->reap());
    sleep(10);
}
