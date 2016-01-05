<?php

require_once 'bootstrap.php';

if($argv[1] == 'run'){
    $app = new App\CheckJob();
    $app->run();
}