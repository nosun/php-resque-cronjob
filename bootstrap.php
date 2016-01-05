<?php

define('BASEPATH', dirname(__FILE__));
define('CONFPATH', BASEPATH . '/config/');
require_once BASEPATH .'/vendor/autoload.php';

use Noodlehaus\Config;
$conf = Config::load(CONFPATH.'redis.php');

date_default_timezone_set('PRC');
Resque::setBackend($conf['host'].':'.$conf['port'],$conf['db'],$conf['password']);


