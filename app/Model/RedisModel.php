<?php namespace App\Model;

use Nosun\Client\Redis;
use Noodlehaus\Config;

class RedisModel {

    private $redis;
    static $job = 'cron_job_';

    public function __construct(){
        $conf = Config::load(CONFPATH.'redis.php');
        $redis = new Redis($conf['host'],$conf['port']);
        $this->redis = $redis;
        $this->redis->auth('rensheng');
        $this->redis->select($conf['db']);
    }

    public function getCronJob($time){
        return $this->redis->sMembers(self::$job.$time);
    }

    public function selectDB($db){
        return $this->redis->select($db);
    }

}