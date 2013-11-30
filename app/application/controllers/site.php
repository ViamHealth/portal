<?php

class Site extends V_Controller {

	public function heartbeat()
	{
		echo "~~";
	}

	public function login($login_type='email')
	{
		$data['title'] = 'Login'; // Capitalize the first letter
		$data['login_type'] = $login_type;
		$this->template('site/login',$data);
	}

	public function signup()
	{
		$data['title'] = 'Signup'; // Capitalize the first letter

		$this->template('site/signup',$data);
	}

	public function forgot_password()
	{
		$data['title'] = 'Forgot Password'; // Capitalize the first letter

		$this->template('site/forgot_password',$data);
	}

	public function loginapi($token)
	{
		$this->session->set_userdata('token',$token);
		$a = $this->apiCall('get','users/me/');;
    	if($a){
    		$user = $a;
	    	$this->session->set_userdata('user',$user);
	    	$this->session->set_userdata('token',$token);
	    	echo "1";	
    	} else {
    		$this->session->unset_userdata('token');
    		echo "0";
    	}
    	
	}

	public function logout()
	{
		$this->session->unset_userdata('user');
		$this->session->unset_userdata('token');
		$this->session->unset_userdata('family');
		redirect('/login', 'refresh');

	}
}