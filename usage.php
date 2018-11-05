<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

# Redis
//$redis = new Redis();
//$redis->pconnect('127.0.0.1');
//$redis->select(0);
//$counter = new \Counter\RedisFileCounter($redis, 'counter-increment');

#Single File
//$counter = new \Counter\SingleFileCounter(__DIR__ . '/counter');

#Multiple File
$counter = new \Counter\SimpleCounter(__DIR__ . '/counter');

while (true) {
    var_dump($counter->increment());
}
