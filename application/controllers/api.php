<?php

 //[todo] - Add multiple checks for user(s) meeting(s)
class Api extends CI_Controller
{
	public function index()
	{
		// if( ! file_exists('application/views/pages/'.$page.'.php'))
		// {
		// 	//Whoops, 404
		// 	show_404();
		// }
		// echo 'API Response:';
		// echo '<br/>';
		// echo uri_string();
		$arguments = explode('/', uri_string());

		#######
		#DEBUG#
		#######
		// print_r($arguments);
		// echo "<br/>End of Debug<br/><br/><br/><br/>";
		########


		//Check arguments for sanity.
		$this->argumentCheck($arguments);

		switch ($arguments[1])
		{
			//Meeting Requested
			case 'meetings':
				echo $this->meetings($arguments);
				break;

			//Users Requested
			case 'users':
				echo $this->users($arguments);
				break;
			//Default
			default:
				echo 'Error No Function Requested';
				break;
		}
		// } else {
		// 	echo 'No function requested';
		// }

	}

	/**
	 *  Checks wether or not the arguments passed to the API are sufficient
	 **/
	private function argumentCheck($arguments)
	{
		// print_r($arguments);
		// echo count($arguments);
		if (count($arguments) > 1)
		{
			if (count($arguments) == 2)
			{
				echo("Error Processing Request. No subfunction requested.");
				die();
			} else {
				return True;
			}
		}else {
			// echo count($arguments);
			// echo 'wat';
			echo "Error Processing Request. No function requested.";
			die();
		}
	}


	/**
	 * 	MEETINGS API
	 **/
	public function meetings($input)
	{

		if (count($input) == 3 && $input[2] != 'put')
		{
			echo "Arguments are missing.";
			die();
		}
		//Placeholders
		$request = $input[2];


		if ($request == 'put')
		{
			// print_r($this->input->post());
			$this->load->model('meetings_model');
			$name     = $this->input->post('name');
			$date     = $this->input->post('date');
			$manager  = $this->input->post('manager');
			$user     = $this->input->post('user');
			$location = $this->input->post('location');
			$invitees = $this->input->post('invitees');
			$notes    = $this->input->post('notes');
			$hash 	  = sha1($name.$data.$manager.$user.$location.$invitees.$notes);
			$data = array(
				'name'     => $name,
				'date'     => $date,
				'manager'  => $manager,
				'user'     => $user,
				'location' => $location,
				'invitees' => $invitees,
				'notes'    => $notes,
				'hash'     => $hash
			 );
			$this->meetings_model->post_meeting($data);

			//pass the data to the model for insertion
			// return json_encode($output);
		} else if($request == 'get')
		{
			$args = $input[3];
			if ($args == 'latest') {
				$this->load->model('meetings_model');
				echo $this->meetings_model->get_latest_hash();
			} else if ($args == 'list') {
				$this->load->model('meetings_model');
				echo json_encode($this->meetings_model->get_list());
			} else if ($args == 'all') {
				$this->load->model('meetings_model');
				echo json_encode($this->meetings_model->get_all_hashes());
			} else if ($args == 'user') {
				$this->load->model('meetings_model');
				echo json_encode($this->meetings_model->get_user_meetings(rawurldecode($input[4])));
			}

		} else if ($request == '')
		{
			echo "Something went wrong";
		}
	}

	/**
	 * 	USERS API
	 **/
	public function users($input)
	{

		if (count($input) == 3)
		{
			echo "Arguments are missing.";
			die();
		}
		//Placeholders
		$request = $input[2];
		$args = $input[3];

		if ($request == "get") {

			if ($args == "online") {
				$this->load->model('user_model');
				echo json_encode($this->user_model->get_online_users());
			}
		}

	}




}