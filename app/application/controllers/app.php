<?php

class App extends V_Controller {


	public function index()
	{
		$data['title'] = 'Food Diary';
		$this->template('fooddiary/index',$data);
		//redirect(viam_url('/files'), 'refresh');
	}

	public function resetsessionuserdata()
	{
		$this->session->unset_userdata('user');
		$this->session->unset_userdata('family');
		$a = $this->apiCall('get','users/me/');;
    	if($a){
    		$user = $a;
	    	$this->session->set_userdata('user',$user);
    	}
    	$family = $this->apiCall('get','users/');
		$this->session->set_userdata('family',$family);
	}
}