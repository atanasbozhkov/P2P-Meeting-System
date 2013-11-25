<?php

class Meetings_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}
	//[todo] - implement meeting push
	public function push_meeting()
	{
		$data = array(
			'username' => $this->input->post('username'),
			'password' => sha1($this->input->post('password'))
			);
		return $this->db->insert('users',$data);
	}

	public function get_latest_meeting()
	{
		$sql = "SELECT * FROM meetings ORDER BY id DESC LIMIT 1";
		$query = $this->db->query($sql);
		foreach( $query->result() as $result )
		{
			return $result;
		}
	}

	public function get_latest_hash()
	{
		$sql = "SELECT hash FROM meetings ORDER BY id DESC LIMIT 1";
		$query = $this->db->query($sql);
		foreach ($query->result() as $result) 
		{
			return $result->hash;
		}
	}

	//[todo] - implement DHT 
	public function get_list()
	{
		$sql = "SELECT * from meetings";
		$query = $this->db->query($sql);
		$return = array();
		foreach ($query->result() as $result) {
			array_push($return, $result);
		}
		return $return;
	}
}

?>