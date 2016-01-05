<?php namespace Jobs;

use Nosun\Client\Socket;
use Noodlehaus\Config;
use App\Model\RedisModel;
use App\ParseCrontab;

class SendCmd {

    private $socket;
    private $redis;

    public function setUp(){
        $conf = Config::load(CONFPATH.'socket.php');
        $this->socket = new Socket($conf['host'],$conf['port'],$conf['type'],$conf['long']);

        if(!$this->socket->connect()){
            $error = 'socket connect error';
            my_log('/data/log/cronJob/doJob.log',$error);
            exit;
        }
        my_log('/data/log/cronJob/doJob.log','123');
        $this->redis = new RedisModel();
        $this->redis->selectDB(0);
    }

    public function perform(){
        my_log('/data/log/cronJob/doJob.log','456');
        $time = $this->args['t'];
        $sets = $this->redis->getCronJob($time);
        my_log('/data/log/cronJob/doJob.log',$time);
        foreach($sets as $row){
            $job = unserialize($row);
            if(isset($job['m']) && isset($job['p']) && isset($job['c'])){
                if(ParseCrontab::parse($job['p']) == true) {
                    $msg = $this->makeCmd($job['m'], $job['c']);
                    $res = $this->socket->send($msg);

                    if (!$res) {
                        $error = 'Cronjob '.$time. ' '. $row .' send message error';
                        my_log('/data/log/cronJob/doJob.log', $error);
                    }else{
                        $error = 'Cronjob '.$time. ' '. $row .' send message success';
                        my_log('/data/log/cronJob/doJob.log', $error);
                    }
                }
            }else{
                $error = 'Cronjob '.$time. ' '. $row .' format error';
                my_log('/data/log/cronJob/doJob.log',$error);
            }
        }
    }

    public function tearDown()
    {
        $this->socket->close();
    }

    private function makeCmd($mac,$cmd){
        $data = array(
            'cmd' => 'command',
            'mac' => $mac,
            'data'=> $cmd
        );

        return json_encode($data,true);
    }

}