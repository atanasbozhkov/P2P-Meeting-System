<?php
function is_logged($username)
{
	if (!$username) 
	{
		if(current_url() != 'home')
		{
			echo current_url();
			// redirect('/', 'location', 301);
		}
		$data['title'] = ucfirst('P2P Meeting System');
		$this->load->view('templates/header', $data);
		$this->load->view('pages/'.$page, $data);
		$this->load->view('templates/footer');
	}
}


?>