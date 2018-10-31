<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

$counter = new \Counter\SimpleCounter(__DIR__ . '/counter');

while (true) {
    var_dump($counter->increment());
    sleep(1);
}
