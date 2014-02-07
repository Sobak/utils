<?php
class db {
	public $queries = array();
	private $mysqli;

	public function db($data) {
		$this->mysqli = @new mysqli($data['host'], $data['user'], $data['pass'], $data['name']);

		if ($this->mysqli->connect_errno) {
			die ('<h1>Database error</h1>');
			return false;
		}
		else {
			$this->mysqli->set_charset('utf8');
		}
	}
	
	public function query($query) {
		$resource = $this->mysqli->query($query);

		if ($resource) {
			$this->queries[] = $query;
			return $resource;
		}
		else {
			echo $this->get_error();
			return false;
		}
	}

	public function escape($data) {
		return $this->mysqli->real_escape_string($data);
	}
	
	public function fetch($result, $mode = '') {
		switch ($mode) {
			case 'ROW':
				return $result->fetch_row();
			
			case 'BOTH':
				return $result->fetch_array();
			
			default:
				return $result->fetch_assoc();
		}
	}
	
	public function fetch_result($query, $mode = '') {
		return $this->fetch($this->query($query), $mode);
	}
	
	public function fetch_all($result, $mode = '') {
		if (method_exists('mysqli_result', 'fetch_all')) {
			switch ($mode) {
				case 'ROW':
					return $result->fetch_all(MYSQLI_NUM);
			
				case 'BOTH':
					return $result->fetch_all(MYSQLI_BOTH);
			
				default:
					return $result->fetch_all(MYSQLI_ASSOC);
			}
		}
		else {
			if ($mode == '')
				$mode = MYSQLI_ASSOC;

			for ($res = array(); $tmp = $result->fetch_array($mode);)  {
				$res[] = $tmp;
			}
			
			return $res;	
		}
	}
	
	public function fetch_all_result($query, $mode = '') {
		return $this->fetch_all($this->query($query), $mode);
	}
	
	public function returned_rows($resource) {
		return $resource->num_rows;
	}
	
	public function modified_rows() {
		return $this->mysqli->affected_rows;
	}
	
	public function get_error() {
		return '['.$this->mysqli->errno.']'.' '.$this->mysqli->error;
	}
	
	public function get_one_record($query) {
		$record = $this->fetch($this->query($query), 'ROW');
		return $record[0];
	}
	
	public function get_insert_id() {
		return $this->mysqli->insert_id;
	}
	
	public function close() {
		$this->mysqli->close();
	}
}