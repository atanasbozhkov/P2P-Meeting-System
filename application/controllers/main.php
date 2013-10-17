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

		if ($this->session->userdata('username')) {
			$data['title'] = ucfirst($page); //Capitalise the first letter
			$data['username'] = $this->session->userdata('username');
			$this->load->view('templates/header', $data);
			$this->load->view('pages/'.$page, $data);
			$this->load->view('templates/footer');
		} else { //Not logged in. Redirect
			if($page != 'home')
			{
			redirect('/', 'location', 301);
			}
			$data['title'] = ucfirst('P2P Meeting System');
			$this->load->view('templates/header', $data);
			$this->load->view('pages/'.$page, $data);
			$this->load->view('templates/footer');
		}
		
		}
}