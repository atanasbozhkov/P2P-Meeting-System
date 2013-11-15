<?php

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	public function index()
	{

		$data['title'] = 'Login';
		//Load the form helper and the form validation library.
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username','Email','required');
		$this->form_validation->set_rules('password','Password','required');

		if ($this->form_validation->run() ===  FALSE) 
		{
		
			$this->load->view('templates/header',$data);
			$this->load->view('users/login',$data);
			$this->load->view('templates/footer');

		} else
		{
			$data['opertaion'] = 'Loggin in';
			$result = $this->user_model->check_user();
			if ($result == true) {
				$this->user_model->update_last_seen($this->session->userdata('username'));
				$data['message'] = 'Login successful '.$this->session->userdata('username');
				redirect('/', 'refresh');
			} else {
				$data['message'] = 'Username or password not matching.<br/> Please try again';
				$this->load->view('templates/header',$data);
				$this->load->view('users/login',$data);
				$this->load->view('templates/footer');			}
			
		}


	}

	public function register()
	{

		$data['title'] = 'Register';
		//Load the form helper and the form validation library.
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->form_validation->set_rules('username','Email','required');
		$this->form_validation->set_rules('password','Password','required');
		$this->form_validation->set_rules('repeat','Repeat pass','required');

		if ($this->form_validation->run() ===  FALSE) 
		{
			
			$this->load->view('templates/header',$data);
			$this->load->view('users/register',$data);
			$this->load->view('templates/footer');

		} else
		{
			$data['opertaion'] = 'Creating user';
			$this->user_model->create_user();
			$data['message'] = 'Registration successeful';
			$this->load->view('templates/header',$data);
			$this->load->view('users/login',$data);
			$this->load->view('templates/footer');
			// $data['message'] = 'Registration successeful';
			// $this->load->view('users/success',$data);

		}

	}

	public function logout()
	{
		session_start();
		$this->session->unset_userdata('username');
	   	session_destroy();
	   	redirect('/', 'refresh');

	}
}