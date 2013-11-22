<?php

class User extends V_Controller {


	public function index()
	{
		$data = array();
		$data['user'] = '';

		$d = $this->apiCall('get','users/'.$this->current_user_id);
		$b = $this->apiCall('get','users/'.$this->current_user_id.'/bmi-profile/');
		
		$d->bmi_profile = $b;
		$this->load->model('User_model','user');

		$this->user->set_data($d);
	
		$data['title'] = 'User';
		$data['user'] = $this->user;
		$data['allow_edit_profile_image'] = false;
		$this->template('users/index',$data);
	}

	public function add()
	{
		$this->load->model('User_model','user');
		$data['title'] = 'Add User';
		$data['user'] = $this->user;
		$data['allow_edit_profile_image'] = false;
		$this->template('users/add',$data);
	}
}