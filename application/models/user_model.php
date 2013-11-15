<?php

class User_model extends CI_Model
{
	public function __construct()
	{
		$this->load->database();
	}

	public function create_user()
	{
		$data = array(
			'username' => $this->input->post('username'),
			'password' => sha1($this->input->post('password'))
			);
		return $this->db->insert('users',$data);
	}

	public function update_last_seen($username)
	{
		$sql = "UPDATE  users SET  last_seen =  '".date('Y-m-d H:i:s')."' WHERE  username = '".$username."'";
		$query = $this->db->query($sql);
	}

	public function get_online_users()
	{
		$sql = "SELECT username from users WHERE last_seen > NOW() - INTERVAL 10 MINUTE";
		$query = $this->db->query($sql);
		$usernames = array();
		foreach( $query->result() as $result )
		{
			array_push($usernames, $result->username);
		}
		return $usernames;
	}
	public function check_user()
	{
		$data = array(
			'username' =>$this->input->post('username'),
			'password' =>sha1($this->input->post('password'))
			);
		$sql = "SELECT COUNT(id) as count FROM users WHERE username = '".$data['username']."' AND password = '".$data['password']."'";
		// echo $sql;
		// die();
		$query = $this->db->query($sql);
		foreach( $query->result() as $result ){
			if ($result->count == 1) 
			{
				$sql_for_id = "SELECT id FROM users WHERE username ='".$data['username']."'";
				$query = $this->db->query($sql_for_id);
				foreach( $query->result() as $result )
				{
					$this->session->set_userdata('user_id',$result->id);
				}
				$this->session->set_userdata('username',$data['username']);
				return true;
			};
		}
		return false;
		
	}
}