<?php

class Site extends V_Controller {


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

	public function loginapi($token)
	{
		$api_url = $this->config->item('api_url').'users/me/';

		$context = stream_context_create(array(
	    'http' => array(
	        'method' => 'GET',
	        'header' => "Authorization: Token ".$token."\r\n"
	      )
	    ));

    	
    	$a = file_get_contents($api_url,false,$context);
    	if($a){
    		$user = json_decode($a);
	    	$this->session->set_userdata('user',$user);
	    	$this->session->set_userdata('token',$token);
	    	echo "1";	
    	} else {
    		echo "0";
    	}
    	
	}
}