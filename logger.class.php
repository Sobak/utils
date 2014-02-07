<?php
class logger {
	public function add_event($error_description, $additional_message, $error_level) {	
		$log = unserialize(file_get_contents('logs/log.log'));
		
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$ip = ip2long($_SERVER['REMOTE_ADDR']);
		$time = time();

		$log[] = array(
			'error_description' => $error_description, 
			'error_level' => (int)$error_level, 
			'additional_message' => $additional_message, 
			'user_agent' => $user_agent,
			'ip' => $ip, 
			'time' => $time);
		
		file_put_contents('logs/log.log', serialize($log));
	}
}