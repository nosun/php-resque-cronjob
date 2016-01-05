<?php namespace Nosun\Client;

use Noodlehaus\Config;

class Socket
{

    public $client;
    private $port;
    private $host;

    public function __construct($host,$port,$type,$long)
    {
        $this->host = $host;
        $this->port = $port;
        $type = constant($type);
        if($long == true){
            $this->client = new \swoole_client(SWOOLE_TCP | SWOOLE_KEEP);
        }else{
            $this->client = new \swoole_client($type);
        }
    }

    public function connect(){

        if ($this->client->connect($this->host, $this->port)) {
            return true;
        }
        return false;
    }


    public function send($msg){

        $result = $this->client->send($msg);
        return $result;
    }

    public function close(){

        $this->client->close();
    }
}