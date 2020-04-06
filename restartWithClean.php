<?php

$s = 'restartWithClean:';
$php = 'php74'; // hosting
// $php = 'php'; // vagrant machine

$commands = [
    $php . ' artisan migrate:refresh --seed',
    $php . ' ' . __DIR__ . '/killQueueWorker.php',
    $php . ' artisan queue:restart',
    $php . ' ' . __DIR__ . '/watchdog_queue_larastore.php',
];

foreach ($commands as $command) {
    echo "$s call '$command'\n";
    sleep(1);
    echo "$s " . exec($command) . "\n\n";
}
