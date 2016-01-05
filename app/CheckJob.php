<?php namespace App;

use App\Model\RedisModel;
use Resque;

class CheckJob {

    private $start;
    private $redis;

    public function __construct(){
        date_default_timezone_set('PRC');
        $this->start = time();
        $this->redis = new RedisModel();
        $this->redis->selectDB(0);
    }

    // 从 Redis 中取出 所有的job
    public function run(){
        $time = date('H_i',$this->start);
        // for test
        //$time = '13_18';
        $sets = $this->redis->getCronJob($time);
        if(!empty($sets)){
//            if(count($sets)>0){
                $res = $this->pushToQueue(array('t' =>$time));
                if(empty($res)){
                    $error='Cronjob '.$time. ' push fail';
                    my_log('/data/log/cronJob/doJob.log',$error);
                }else{
                    $error='Cronjob '.$time. ' push ok, Id '. $res;
                    my_log('/data/log/cronJob/doJob.log',$error);
                }
//            }
        }
    }

    private function pushToQueue($job){
        $jobId = Resque::enqueue('default','Jobs\SendCmd',$job, true);
        return $jobId;
    }

}