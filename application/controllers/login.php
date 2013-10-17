<?php

class Login extends CI_Controller
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
			$rows = $this->user_model->check_user();
			if ($rows == 1) {
				$data['message'] = 'Woop';
				$data['rows'] =  $rows;
				$this->load->view('users/success',$data);
			} else {
				$data['message'] = 'Username or password not matching. Please try again';
				$data['rows'] =  $rows;
				$this->load->view('users/login',$data);
			}
			
		}


	}
}