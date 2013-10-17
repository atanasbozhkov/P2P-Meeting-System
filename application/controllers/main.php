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
		if ($this->session->userdata('username')) 
		{
			$data['username'] = $this->session->userdata('username');
		}
		$this->load->view('templates/header', $data);
		$this->load->view('templates/menu', $data);
		$this->load->view('pages/'.$page, $data);
		$this->load->view('templates/footer');
			
	}



	private function logged_in()
	{
		if (!$this->session->userdata('username')) 
		{
				redirect('/', 'location', 301);
		}
	}

	public function meetings()
	{
		//Check if logged in
		$this->logged_in();
		//Proceed loading the page views
		$data['username'] = $this->session->userdata('username');
		$data['title'] = 'Meetigns';
		
		$this->load->view('templates/header', $data);
		$this->load->view('templates/menu', $data);
		$this->load->view('pages/meetings', $data);
		$this->load->view('templates/footer');

	}
}