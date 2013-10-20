<?php

class Api extends CI_Controller 
{
	public function index()
	{
		if( ! file_exists('application/views/pages/'.$page.'.php'))
		{
			//Whoops, 404
			show_404();
		}
		echo 'hello';
			
	}

	public function meeting_new($value='')
	{
		$name     = $this->input->post('name');
		$date     = $this->input->post('date');
		$location = $this->input->post('location');
		$invitees = $this->input->post('invitees');
		$notes    = $this->input->post('notes');

		$output = array(
			'name'     => sha1($name),
			'date'     => sha1($date),
			'location' => sha1($location),
			'invitees' => sha1($invitees),
			'notes'    => sha1($notes)
		 );
		// echo 'hello';
		echo json_encode($output);
	}

}