<?php namespace Nosun\Client;

use Noodlehaus\Config;

class Redis {

	protected $redis;

	public function __construct($host = '127.0.0.1', $port = 6379, $timeout = 0.0,$pass = null){

		$redis = new \redis;
		$redis->pconnect($host, $port, $timeout);

		if($pass){
			$redis->auth($pass);
		}

		$this->redis = $redis;
	}

	/**
	 * Dynamically make a Redis command.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		return call_user_func_array(array($this->redis, $method), $parameters);
	}

}
