<?php

require 'bootstrap.php';

if(empty($argv[1])) {
    die('Specify the name of a job');
}

if(empty($argv[2])) {
    die('Specify the argument for a job');
}

$args = array(

    'name' => 'nosun'
);


// You can also use a DSN-style format:
//Resque::setBackend('redis://user:pass@127.0.0.1:6379');
//Resque::setBackend('redis://user:pass@a.host.name:3432/2');

$jobId = Resque::enqueue('default', $argv[1], $args, true);
echo "Queued job ".$jobId."\n\n";