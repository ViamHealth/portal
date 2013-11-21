<?php

class User extends V_Controller {


	public function index()
	{

		$d = $this->apiCall('get','users/'.$this->current_user_id);
		
//		var_dump($d);
		$b = $this->apiCall('get','users/'.$this->current_user_id.'/bmi-profile/');
		//var_dump($b);
		$d->bmi_profile = $b;

		$data['user'] = $d;
		$data['title'] = 'User';
		$this->template('users/index',$data);
	}
}