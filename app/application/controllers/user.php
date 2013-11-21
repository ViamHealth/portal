<?php

class User extends V_Controller {


	public function index()
	{

		$d = $this->apiCall('get','users/'.$this->current_user_id);
		
//		var_dump($d);
		$b = $this->apiCall('get','users/'.$this->current_user_id.'/bmi-profile/');
		//var_dump($b);
		

		switch($b->bmi_classification){
			case '1' : $b->bmi_classification_text = 'Underweight';
				break;
			case '2' : $b->bmi_classification_text = 'Normal range';
				break;
			case '3' : $b->bmi_classification_text = 'Overweight';
				break;
			case '4' : $b->bmi_classification_text = 'Obese';
				break;
			default : $b->bmi_classification_text = '';
				break;
		}

		$d->bmi_profile = $b;
		$data['user'] = $d;
		$data['title'] = 'User';
		$this->template('users/index',$data);
	}
}