<?php

class Main extends CI_Controller 
{
	public function index($page = 'home')
	{
		if( ! file_exists('application/views/pages/'.$page.'.php'))
		{
			//Whoops, 404
			show_404();
		}

		$data['title'] = ucfirst($page); //Capitalise the first letter
		/**
		* Check if the user is logged in. 
		* If true - pass the username to the view
		* If false - redirect to the login screen.
		**/
		if ($this->session->userdata('username')) 
		{
			$data['username'] = $this->session->userdata('username');
		} else {
			redirect('/login','location',301);
		}

		//Get the online users from the API controller and remove the current user from them.

		$data['peers'] = $this->peers_array();
		$this->load->view('templates/header', $data);
		$this->load->view('templates/menu', $data);
		$this->load->view('pages/'.$page, $data);
		$this->load->view('templates/footer');
			
	}

	private function peers_array()
	{
		$result= json_decode(file_get_contents(site_url('api/users/get/online')));
		$normalised = array_diff($result, array($this->session->userdata('username')));
		return json_encode(array_values((array) $normalised));
	}


	private function logged_in()
	{
		$this->load->model('user_model');
		if (!$this->session->userdata('username')) 
		{
				redirect('/', 'location', 301);
		} else
		{
			// print_r($this->user_model->get_online_users());
			$this->user_model->update_last_seen($this->session->userdata('username'));
		}
	}

	public function meetings()
	{
		//Check if logged in
		$this->logged_in();
		//Proceed loading the page views
		$data['user_id'] = $this->session->userdata('user_id');
		$data['username'] = $this->session->userdata('username');
		$data['title'] = 'Meetings';
		$data['peers'] = $this->peers_array();

		//Before loading the screen we should ask about the latest version
		//of the meeting list.
		$data['list'] = file_get_contents(site_url('/api/meetings/get/latest'));
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/menu', $data);
		$this->load->view('pages/meetings', $data);
		$this->load->view('templates/footer');

	}

	public function test()
	{
		//Check if logged in
		$this->logged_in();
		//Proceed loading the page views
		$data['username'] = $this->session->userdata('username');
		$data['title'] = 'Meetigns';
		echo 'wat<br/>';
		$this->load->model('meetings_model');
		print_r($this->meetings_model->get_latest_hash());
		// $this->load->view('templates/header', $data);
		// $this->load->view('templates/menu', $data);
		// $this->load->view('pages/meetings', $data);
		// $this->load->view('templates/footer');

	}
}