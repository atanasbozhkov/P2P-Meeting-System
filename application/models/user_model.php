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
			if ($result->count == 1) {
				$this->session->set_userdata('username',$data['username']);
				return true;
			};
		}
		return false;
		
	}
}